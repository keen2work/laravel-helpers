<?php

namespace EMedia\Helpers\Console\Commands\Production;


use Illuminate\Console\Command;

class ConnectDeployKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'setup:production:connect-deploy-keys
								{--public-key-path : Your public key to be used}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Add SSH public key to Bitbucket private repositories.';

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        $schemaPath = base_path('composer.json');
        $publicKeyPath = $this->option('public-key-path');

        if (file_exists($publicKeyPath)) {
            $publicKey = file_get_contents($publicKeyPath);
        } else {
            $publicKey = $this->ask('What is the PUBLIC KEY to be used?');
        }

        if (!file_exists($schemaPath)) {
            $this->error("Cannot read file `$schemaPath`. Aborting...");
            return;
        }

        $json = json_decode(file_get_contents($schemaPath));
        if (empty($json->repositories)) {
            $this->info('No private repositories found. Aborting...');
            return;
        }

        $appName = config('app.name');
        if ($appName === 'Laravel' || empty($appName)) {
            $this->error('Set the app name in your .env file first. Aborting...');
            return;
        }

        if (strpos($publicKey, 'PRIVATE') !== false) {
            $this->error('Seems like you have entered a PRIVATE key. Do not distribute PRIVATE KEYS!!!. Aborting...');
            return;
        }

        $label = \Illuminate\Support\Str::snake($appName) . '_' . php_uname("n");

        $label = $this->ask('Enter a label for the key', $label);
        if (!$this->confirm("Add all keys with a label `{$label}`?", false)) {
            $this->error('Aborting...');
            return;
        }

        $this->info('Adding keys requires a BitBucket app consumer.');
        $clientKey = $this->ask('Enter Client Key');
        $clientSecret = $this->secret('Enter Client Secret');

        check_all_present($clientKey, $clientSecret, $label, $publicKey);

        $accessToken = $this->getAccessToken($clientKey, $clientSecret);

        if ($accessToken == null) {
            $this->error('Could not retrieve a valid access token. Aborting...');
            return;
        }

        foreach ($json->repositories as $repository) {
            if (strtolower($repository->type) !== 'vcs') {
                $this->info("Skipping non VCS URL `{$repository->url}`");
                continue;
            }

            $this->addKey($repository->url, $label, $publicKey, $accessToken);
        }
    }

    protected function getAccessToken($clientKey, $clientSecret)
    {
        $accessToken = null;

        // https://developer.atlassian.com/bitbucket/api/2/reference/meta/authentication#oauth-2
        // curl -X POST -u "CLIENT-KEY:CLIENT-SECRET" https://bitbucket.org/site/oauth2/access_token -d grant_type=client_credentials

        $ch = curl_init('https://bitbucket.org/site/oauth2/access_token?grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERPWD, $clientKey . ':' . $clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'grant_type' => 'client_credentials',
        ]);

        // RESPONSE FORMAT: {"access_token": "ACCESS-TOKEN", "scopes": "repository:write repository:admin", "expires_in": 7200, "refresh_token": "E72FJL69JLxgnCXSDE", "token_type": "bearer"}
        $response = json_decode(curl_exec($ch));
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code < 200 || $code >= 300) {
            $this->error("Could not receive an access token for given client key and client secret.");
            // print_r($response);
        } else {
            if (isset($response->access_token, $response->scopes)) {
                if (strpos($response->scopes, 'repository:admin') > -1) {
                    $accessToken = $response->access_token;
                } else {
                    $this->error("Provided client key does not have admin privileges.");
                    // print_r($response);
                }
            } else {
                $this->error("Could not receive an access token or access scopes for given client key and client secret.");
                // print_r($response);
            }
        }

        curl_close($ch);

        return $accessToken;
    }

    protected function addKey($repository, $label, $key, $accessToken)
    {
        if (strpos($repository, 'git@bitbucket.org:elegantmedia') === false) {
            $this->error("Skipping unsupported repository: " . $repository);
            return;
        }

        $sections = explode(':', $repository);
        if (is_countable($sections) && count($sections) === 2) {
            $slug = substr($sections[1], 0, strrpos($sections[1], '.'));
            $this->info('Adding key to: ' . $slug);

            // https://developer.atlassian.com/bitbucket/api/2/reference/resource/repositories/%7Busername%7D/%7Brepo_slug%7D/deploy-keys#post
            /*
                curl -XPOST \
                -H "Authorization: Bearer ACCESS-TOKEN" \
                -H "Content-type: application/json" \
                https://api.bitbucket.org/2.0/repositories/USER/REPOSITORY/deploy-keys -d \
                '{
                    "key": "ssh-rsa AAAAB3NzaC1yc2EAA...Bdq5 user@domain",
                    "label": "site.com"
                }'
            */

            $ch = curl_init('https://api.bitbucket.org/2.0/repositories/' . $slug . '/deploy-keys');
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ];
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'key' => $key,
                'label' => $label,
            ]));

            $response = json_decode(curl_exec($ch));
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($code < 200 || $code >= 300) {
                if (isset($response->key) && is_countable($response->key) && count($response->key)) {
                    foreach ($response->key as $info) {
                        if(isset($info->message)) {
                            $this->info($info->message);
                        }
                    }
                } else {
                    print_r($response);
                }
            } else {
                $this->info('Key successfully added to: ' . $slug);
            }

            curl_close($ch);
        }
    }
}
