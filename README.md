# Storage Bundle for Symfony

This bundle makes it easy to upload files to remote storage services like Amazon S3, Cloudflare R2, Google Cloud Storage, and Azure Blob Storage. Additionally, it provides a mock storage client to easily test your integrations without requiring a network connection.

## Installation

Install the bundle using Composer:

```
composer require 1tomany/storage-bundle
```

If you're using Amazon S3 or an S3 compatible provider like Cloudflare R2, you'll also need to install the AWS SDK bundle provided by Amazon:

```
composer require aws/aws-sdk-php-symfony
```

Going forward, any mention of Amazon S3 or AWS assumes you're using Amazon S3 itself or a compatible provider.

## Configuration

Below is the complete configuration for this bundle. To customize it for your Symfony application, create a file named `onetomany_storage.yaml` in `config/packages/` and make the necessary changes.

```yaml
onetomany_storage:
    client: "amazon"
    bucket: "storage-bucket"
    custom_url: ~

    amazon_client:
        bucket: "amazon-bucket"
        custom_url: "https://dev.app-cdn.com"
        s3_client: "s3_client_service_id"

    mock_client:
        bucket: "mock-bucket"
        custom_url: "https://mock.app-cdn.com"
```

#### `onetomany_storage.client`

The storage client to use. Possible values are:

- `"amazon"` Amazon S3 compatible client
- `"mock"` A mock client for testing

These values correspond to the `key` for each service with the tag `onetomany.storage.client`. You can add your own client by implementing the `OneToMany\StorageBundle\Contract\Client\ClientInterface` interface and tagging it with the tag `onetomany.storage.client` and a `key` value other than the ones above.

#### `onetomany_storage.client`

The bucket where files will be uploaded.

#### `onetomany_storage.custom_url`

The URL used to reference the uploaded file instead of the canonical URL returned by the storage service. Set this value if you use Amazon CloudFront or a public Cloudflare R2 bucket domain to get a publicly accessible file URL:

```yaml
onetomany_storage:
    custom_url: "https://files.app-cdn.com"
```

When set, if an object with the key `users/10/files/avatar.png` was uploaded, the following URL would be returned:

```
https://files.app-cdn.com/users/10/files/avatar.png
```

### Configuring Amazon S3

Installing the `aws/aws-sdk-php-symfony` package will create a file named `config/packages/aws.yaml` and update the `.env` file with following section:

```env
###> aws/aws-sdk-php-symfony ###
AWS_KEY=not-a-real-key
AWS_SECRET=@@not-a-real-secret
###< aws/aws-sdk-php-symfony ###
```

You should add the following environment variable for modern versions of Symfony as well:

```env
AWS_MERGE_CONFIG=true
```

I highly recommend taking advantage of [Symfony secrets](https://symfony.com/doc/current/configuration/secrets.html) to store encrypted values of the `AWS_KEY` and `AWS_SECRET` environment variables and removing them directly from the `.env` file.

### Configuring Cloudflare R2

The Cloudflare R2 service is an Amazon S3 compatible provider, which means you can use the AWS SDK and bundle **as is** with one additional environment variable:

```env
AWS_ENDPOINT="https://<account_id>.r2.cloudflarestorage.com"
```

Replace `<account_id>` with the account ID found in the Cloudflare R2 dashboard; it's usually a 32 character hexadecimal string like `45242ae44b7b9f01930a43d617f9f7a8`.

You'll also have to update the `config/packages/aws.yaml` file to use a different region and this environment variable. Change the `region` key from `us-east-1` to `auto`, and add the `endpoint` key:

```yaml
aws:
    version: latest
    region: auto
    endpoint: "%env(AWS_ENDPOINT)%"
    credentials:
        key: "%env(AWS_KEY)%"
        secret: "%env(AWS_SECRET)%"
```

## Using actions

This bundle registers a factory in the the Symfony container that will create a storage client object. Each storage client class implements a common interface: `OneToMany\StorageBundle\Contract\Client\ClientInterface`. When an object of this type is injected into a class, the Symfony container will create the client object defined by the value stored in the `onetomany_storage.client` property.

```php
<?php

namespace App\File\Action\Handler;

use OneToMany\StorageBundle\Contract\Client\ClientInterface;
use OneToMany\StorageBundle\Request\UploadRequest;

final readonly class UploadFileHandler
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function __invoke(string $path, string $format, string $key): void
    {
        // @see OneToMany\StorageBundle\Response\UploadResponse
        $response = $this->client->act(new UploadRequest($path, $format, $key));
    }
}
```

However, I **do not** recommend using an instance of the `OneToMany\StorageBundle\Contract\Client\ClientInterface` interface directly. Instead, you should use an action class. There are three action interfaces:

- `OneToMany\StorageBundle\Contract\Action\DeleteActionInterface`
- `OneToMany\StorageBundle\Contract\Action\DownloadActionInterface`
- `OneToMany\StorageBundle\Contract\Action\UploadActionInterface`

Each of these expose a single public function, `act()`, which calls the underlying `OneToMany\StorageBundle\Contract\Client\ClientInterface` method to perform the action requested.

The code above would be rewritten as follows:

```php
<?php

namespace App\File\Action\Handler;

use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Request\UploadRequest;

final readonly class UploadFileHandler
{
    public function __construct(private UploadActionInterface $uploadAction)
    {
    }

    public function __invoke(string $path, string $format, string $key): void
    {
        // @see OneToMany\StorageBundle\Response\UploadResponse
        $response = $this->uploadAction->act(new UploadRequest($path, $format, $key));
    }
}
```

### Action philosophy

The difference is subtle, but I prefer using the action classes for a few reasons:

1. The interface name indicates the action being performed. By injecting an object of type `OneToMany\StorageBundle\Contract\Action\UploadActionInterface`, it's clear that you intend for this service to upload a file.
2. Any non-client-specific pre or post-processing can be handled in the `act()` method rather than reimplementing it in each storage client class.
3. They can be mocked in tests easier. Because a concrete object is being injected, only the `act()` method needs to be mocked. Mocking (or creating an anonymous class of) the `OneToMany\StorageBundle\Contract\Client\ClientInterface` is more difficult and often overkill for a test that's only testing one action.

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
