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
STORAGE_SERVICE="aws"
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

- `aws` Amazon S3
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
Installing the `aws/aws-sdk-php-symfony` package will create a file named `./config/packages/aws.yaml` and update the `.env` file with new environment variables.


## Credits
- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License
The MIT License
