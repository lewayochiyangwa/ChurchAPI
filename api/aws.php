<?php
// Include the SDK using the Composer autoloader
//require '../vendor/composer/autoload_real.php';
require '../vendor/autoload.php';

//use aws\aws-sdk-php\src\S3\S3Client;
//use aws\aws-sdk-php\src\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;


// AWS configuration
$bucketName = 'charleswayo';
$accessKeyId = 'AKIAZO2LKAUNZDB2WBED';
$secretAccessKey = 'djNOvY0n4psuHMU0ufl5LoYKGsPyo9ya3JpEEwhO';
$region = 'us-east-1'; // e.g., 'us-east-1'

// Path to the file you want to upload
$filePath = 'C:\\Users\\Leroy Chiyangwa\\Videos\\comedies.txt';

// Create an S3 client
$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'credentials' => [
        'key' => $accessKeyId,
        'secret' => $secretAccessKey
    ]
]);

try {
    // Upload the file to S3
    $result = $s3Client->putObject([
        'Bucket' => $bucketName,
        'Key' => basename($filePath),
        'SourceFile' => $filePath
    ]);

    // Print the URL of the uploaded file
    echo 'File uploaded successfully. URL: ' . $result['ObjectURL'];
} catch (AwsException $e) {
    // Print an error message if the upload fails
    echo 'Error uploading file: ' . $e->getMessage();
}
?>
