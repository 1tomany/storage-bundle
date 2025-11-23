# Storage Bundle for Symfony
This bundle makes it easy to upload files to remote storage services like Amazon S3, Cloudflare R2, Google Cloud Storage, and Azure Blob Storage. Additionally, it provides a mock storage service to easily test your integrations without requiring a network connection.

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

### Updating `.env` and `.env.test`
This bundle does not have a Symfony Flex recipe yet, so you'll have to manually update your `.env` file by adding the following section:

```env
###> 1tomany/storage-bundle ###
STORAGE_SERVICE="s3"
STORAGE_BUCKET="my-bucket-name"
STORAGE_CUSTOM_URL=
###< 1tomany/storage-bundle ###
```

And update the `.env.test` file by adding the following section:

```env
###> 1tomany/storage-bundle ###
STORAGE_SERVICE="mock"
###< 1tomany/storage-bundle ###
```

#### `STORAGE_SERVICE`
The storage provider to use. Possible values are:

- `s3` Amazon S3 compatible service
- `mock` A mock service for testing

#### `STORAGE_BUCKET`
The bucket where files will be uploaded.

#### `STORAGE_CUSTOM_URL`
The URL to use to reference the uploaded file instead of the canonical URL returned by the provider. Set this value if you use Amazon CloudFront or a public Cloudflare R2 bucket domain to get a publicly accessible file URL:

```env
STORAGE_CUSTOM_URL="https://my-files.my-custom-cdn.com"
```

When set, if an object named `users/10/files/avatar.png` was uploaded, the following URL would be returned:

```
https://my-files.my-custom-cdn.com/users/10/files/avatar.png
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
This bundle registers a factory in the the Symfony container that will create a storage client provider service object. Each storage provider service class implements a common interface: `OneToMany\StorageBundle\Contract\Client\StorageClientInterface`. When a variable of this type is injected, the Symfony container will create the concrete storage provider service object defined by the `STORAGE_SERVICE` environment variable.

```php
<?php

namespace App\File\Action\Handler;

use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Request\UploadFileRequest;

final readonly class UploadFileHandler
{
    public function __construct(private StorageClientInterface $storageClient)
    {
    }

    public function __invoke(string $path, string $mimeType, string $key): void
    {
        $response = $this->storageClient->act(new UploadFileRequest($path, $mimeType, $key));

        // assert($record instanceof UploadedFileResponseInterface);
    }
}
```

However, I **do not** recommend using an instance of the `StorageClientInterface` interface directly. Instead, you should use an action class. There are three action interfaces:

- `OneToMany\StorageBundle\Contract\Action\DeleteFileActionInterface`
- `OneToMany\StorageBundle\Contract\Action\DownloadFileActionInterface`
- `OneToMany\StorageBundle\Contract\Action\UploadFileActionInterface`

Each of these expose a single public function, `act()`, which calls the actual `StorageClientInterface` method to perform the action requested.

The code above would be rewritten as follows:

```php
<?php

namespace App\File\Action\Handler;

use OneToMany\StorageBundle\Contract\Action\UploadFileActionInterface;
use OneToMany\StorageBundle\Request\UploadFileRequest;

final readonly class UploadFileHandler
{
    public function __construct(private UploadFileActionInterface $uploadFileAction)
    {
    }

    public function __invoke(string $path, string $mimeType, string $key): void
    {
        $record = $this->uploadFileAction->act(new UploadFileRequest($path, $mimeType, $key));
        
        // assert($record instanceof UploadedFileResponseInterface);
    }
}
```

### Action philosophy
The difference is subtle, but I prefer using the action classes for a few reasons:

1. The interface name indicates the action being performed. By injecting an object of type `UploadFileActionInterface`, it's clear that you intend for this service to upload a file.
2. Any non-client-specific pre or post-processing can be handled in the `act()` method rather than reimplementing it in each storage client class.
3. They can be mocked in tests easier. Because a concrete object is being injected, only the `act()` method needs to be mocked. Mocking (or creating an anonymous class of) the `ServiceClientInterface` is more difficult and often overkill for a test that's only testing one action.

## Credits
- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License
The MIT License
