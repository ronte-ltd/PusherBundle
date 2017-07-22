PusherBundle
============

This is symfony integration of Pusher REST API Client. For more informationo about Pusher see [Pusher official page](https://pusher.com). For more information about the client see [Pusher official REST API Client source](https://github.com/pusher/pusher-http-php).

See also [Slanger source](https://github.com/stevegraham/slanger) and [Slanger docker image](https://hub.docker.com/r/antillion/slanger/).

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ronte-ltd/pusher-bundle "~1"
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

``` php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new RonteLtd\PusherBundle\RonteLtdPusherBundle(),
        );
    }
}
```

### Step 3: Configure the bundle

``` yaml
# app/config/config.yml

# ...
ronte_ltd_pusher:
    auth_key: # Your auth key. String
    secret:   # Your secret. String
    app_id:   # Your app id. Int
    gearman_server: # Gearman server. String
    gearman_port: # Gearman port. String
    bg_worker_id: # This will be a prefix for a background function in case multiple projects on a server using this command.
    # Additonally you may specify some custom options (optional):
    options:
        scheme:            # http / https
        host:              # Api server implemetation host
        port:              # Api server implementation port
        timeout:           # HTTP timeout
        encrypted:         # true / false
        cluster:           # ???
        curl_options:      # Array of custom curl options (see http://php.net/manual/en/curl.constants.php)
        notification_host: # ???
        notification_port: # ???
```

## Usage:

``` php
<?php

// ...

$pusher = $container->get('ronte_ltd_pusher.pusher');

/* @var $data mixed */
$pusher->trigger("some-channel", "some-event", $data);

// or with multiple channels:
$pusher->trigger(["some-channel1", "some-channel2"], "some-event", $data);

// or on background:
$pusher->addPush("some-channel1", "some-event", $data);
```

For more information see [official client page](https://github.com/pusher/pusher-http-php/blob/master/README.md) and [doc page](https://pusher.com/docs/rest_api).
