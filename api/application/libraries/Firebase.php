<?php
require __DIR__ . '/../../vendor/autoload.php';
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\ServiceAccount;
use Google\Cloud\Storage\StorageClient;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\RawMessageFromArray;
use Kreait\Firebase\Exception\FirebaseException;

class Firebase
{
    private $firestore;
    private $database;
    private $bucketName;
  
    public function __construct()
    {    
    // =================factory instance
        $this->factory = (new Factory)->withServiceAccount(__DIR__.'/../../application/config/code-bright-uat-firebase-adminsdk-tv6dy-f4a0f4765f.json');
        
        $this->storage = (new Factory())
        ->withServiceAccount(__DIR__.'/../../application/config/code-bright-uat-firebase-adminsdk-tv6dy-f4a0f4765f.json')
            ->withDefaultStorageBucket('code-bright-uat.appspot.com')
            ->createStorage();
        //===================firestore instance
        $firestore =  $this->factory->createFirestore();
        //=========database instance
        $database =  $firestore->database();
        $this->messaging = $this->factory->createMessaging();
    }

    public function getMessages($tutorial_id){
        $firestore =  $this->factory->createFirestore();
        $database =  $firestore->database();
        $citiesRef = $database->collection('messages')->orderBy('timestamp', 'asc');
         $documents = $citiesRef->documents();
         $messages = array();
         foreach ($documents as $message) {
            if ($message->exists()) {
                $data = $message->data();
                if($data['tutorial_id'] == $tutorial_id)
                {
                    array_push($messages, $data);   
                }
                 
            }
        }
        return $messages;
    }
    
    public function sendMessage(array $data) {
        $firestore =  $this->factory->createFirestore();
        $database =  $firestore->database();
        $collecRef = $database->collection('messages')->newDocument();
        $collecRef->set($data);
    }

        /**
     * Upload a file.
     *
     * @param string $bucketName The name of your Cloud Storage bucket.
     *        (e.g. 'my-bucket')
     * @param string $objectName The name of your Cloud Storage object.
     *        (e.g. 'my-object')
     * @param string $source The path to the file to upload.
     *        (e.g. '/path/to/your/file')
     */
    public function uploadFileToStorage($file) {   
        $objectName = rand()."-".$file["file"]["name"];
        $object = $this->storage->getBucket()->upload(file_get_contents($file["file"]["tmp_name"]), [
        'name' => $objectName,
        ]);
        $object->update(
            ['acl' => []],
            ['predefinedAcl' => 'PUBLICREAD']
        );
        return $publicUrl = "https://{$this->storage->getBucket()->name()}.storage.googleapis.com/{$objectName}";
    }
    
        /**
     * Download an object from Cloud Storage and save it as a local file.
     *
     * @param string $bucketName The name of your Cloud Storage bucket.
     *        (e.g. 'my-bucket')
     * @param string $objectName The name of your Cloud Storage object.
     *        (e.g. 'my-object')
     * @param string $destination The local destination to save the object.
     *        (e.g. '/path/to/your/file')
     */
    public function downloadObject(string $bucketName, string $objectName, string $destination): void
    {
        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object($objectName);
        $object->downloadToFile($destination);
        printf(
            'Downloaded gs://%s/%s to %s' . PHP_EOL,
            $bucketName,
            $objectName,
            basename($destination)
        );
    }

        /**
     * Delete an object.
     *
     * @param string $bucketName The name of your Cloud Storage bucket.
     *        (e.g. 'my-bucket')
     * @param string $objectName The name of your Cloud Storage object.
     *        (e.g. 'my-object')
     */
    function deleteObject(string $bucketName, string $objectName): void
    {
        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object($objectName);
        $object->delete();
        printf('Deleted gs://%s/%s' . PHP_EOL, $bucketName, $objectName);
    }

    public function getCount($user_id) {
        $firestore =  $this->factory->createFirestore();
        $database =  $firestore->database();
        $citiesRef = $database->collection('messages')->orderBy('timestamp', 'asc');
         $documents = $citiesRef->documents();
         $messages = array();
         foreach ($documents as $message) {
            if ($message->exists()) {
                $data = $message->data();
                if($data['senderId'] == $user_id)
                {
                    array_push($messages, $data);   
                }  
            }
        }
        return count($messages);
    }
 
    public function sendNotification($data) {
        
        $topic = $data['topic'];
        if($topic === 'courseSuggest') {
            $title = 'New Course Purchase Request';
            $body = 'Course Purchase Request';
            $message = $this->privatePushNotificationWrapper($data, $title, $body);
        }else if($topic === 'eventSuggest') {
            $title = 'New Event Purchase Request';
            $body = 'Event Purchase Request';
            $message = $this->privatePushNotificationWrapper($data, $title, $body);
        }else if($topic === 'tutorialSubscriptionRequest') {
            $title = 'New Tutorial Subscription Purchase Request';
            $body = 'Tutorial Subscription Purchase Request';
            $message = $this->privatePushNotificationWrapper($data, $title, $body);
        }else {
            $title = 'New Parent Request';
            $body = 'Parent Request';
            $message = $this->privatePushNotificationWrapper($data, $title, $body);
        }

        try {
         
            $result = $this->messaging->send($message);
          
        } catch (MessagingException $e) {
            // echo $e->getMessage();
            // print_r($e->errors());
            // die("okl");
            // $result = null;
        }

      return $result;
    }

    private function privatePushNotificationWrapper($data, $title, $body){
        
        if(isset($data['title'])){
            $title = $data['title'];
        }else{
            $title = 'default'; 
        }

        if(isset($data['body'])){
            $body = $data['body'];
        }else{
            $body = 'Default'; 
        }
        
        if(isset($data['student_id'])){
            $student_id = $data['student_id'];
        }else{
            $student_id = 0; 
        }
      
        $message = CloudMessage::fromArray([
            'token' => $data['firebase_token'],
            'data' => [
                'user_id' => $data['id'],
                'student_id' => $student_id
            ],
           'android' => [
               "priority" => "high",
               "notification" => [
                   "channel_id"=> "com.dbestech.chatty.message",
                   'title' => $title,
                   'body' => $body,
                   ]
               ],
            'apns' => [
            // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#apnsconfig
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'mutable-content'=>1, 
                    'content-available'=>1,
                    'badge' => 1,
                    'sound' =>'ding.caf'
                ],
            ],
           ],
        ]);

        return $message;
    }
}