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
     //=========massesing instance
    $this->messaging = $this->factory->createMessaging();
    }

    public function getMessages($tutorial_id){
        $messages = array();
        try {
            $firestore =  $this->factory->createFirestore();
            $database =  $firestore->database();
            $messageRef = $database->collection('messages');
            $query =   $messageRef->orderBy('timestamp','asc');
            $documents = $query->documents();
            foreach ($documents as $message) {
                if ($message->exists()) {
                    $data = $message->data();
                    if($data['tutorial_id'] == $tutorial_id)
                    {
                        array_push($messages, $data);   
                    }
                    
                }
            }
            $sorted = usort($messages, array($this,'array_sort'));
            return $messages;
      
        } catch (FirebaseException $e) {
            echo 'An error has occurred while working with the SDK: '.$e->getMessage;
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
    public function uploadFileToStorage($files) {  
        $publicUrl = array();
        foreach($files as $file){
        $objectName = rand()."-".$file["name"];
        $object = $this->storage->getBucket()->upload(file_get_contents($file["tmp_name"]), [
            'name' => $objectName,
            ]);
        $object->update(
            ['acl' => []],
            ['predefinedAcl' => 'PUBLICREAD']
        );
        array_push($publicUrl, "https://{$this->storage->getBucket()->name()}.storage.googleapis.com/{$objectName}");
        }
        
        return $publicUrl ;
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
    function downloadObject(string $bucketName, string $objectName, string $destination): void
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

      /**
     * Notfication message
     *
     * @param string $topic Topic for notification ubscribe.
     *        (e.g. 'quiz-result')
     * @param string $message Data to send in notification.
     *        (e.g. 'message')
     */

    public function sendNotification($data) {
        $topic = $data['topic'];
        $notificationFor = $data['notificationFor'];
        $title = $data['title'];
        $body = $data['body'];
        $main_quiz_id = !empty($data['main_quiz_id']) ? $data['main_quiz_id'] :  null;
        $type = !empty($data['type']) ? $data['type'] :  null;
        if($topic === 'event_announcement' && $notificationFor === 'Event') {
            $message = $this->pushNotificationWrapper($topic, $title, $body,  $type, $main_quiz_id );
        }else if($topic === 'event_announcement' && $notificationFor === 'Announcement') {
            $message = $this->pushNotificationWrapper($topic, $title, $body,  $type, $main_quiz_id );
        }else if ($topic === 'event_announcement' && $notificationFor === 'newQuiz'){
            $message = $this->pushNotificationWrapper($topic, $title, $body,  $type, $main_quiz_id );
        }else {
            if($topic == 'homework_status'){
                $message = CloudMessage::fromArray([
                    'token' => $data['firebase_token'],
                    'data' => [
                        'user_id' => $data['id'],
                        'student_id' => $data['student_id'],
                        'type' => $type,
                        'main_quiz_id' => $main_quiz_id
                    ],
                   'android' => [
                       "priority" => "high",
                       "notification" => [
                           "channel_id"=> "com.dbestech.chatty.message",
                           'title' => $data['title'],
                           'body' => $data['body'],
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
                                'title' => $data['title'],
                                'body' => $data['body'],
                            ],
                            'mutable-content'=>1, 
                            'content-available'=>1,
                            'badge' => 1,
                            'sound' =>'ding.caf'
                        ],
                    ],
                   ],
                   ]);
            }else{ 
                $message = CloudMessage::fromArray([
                    'token' => $data['firebase_token'],
                    'data' => [
                        'user_id' => $data['id'],
                        'student_id' => $data['student_id'],
                        'type' => $type,
                        'main_quiz_id' => $main_quiz_id,
                    ],
                'android' => [
                    "priority" => "high",
                    "notification" => [
                        "channel_id"=> "com.dbestech.chatty.message",
                        'title' => $data['title'],
                        'body' => $data['body'],
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
            }
        }

        try {
            $result = $this->messaging->send($message);
        } catch (MessagingException $e) {
            echo $e->getMessage();
            print_r($e->errors());
            $result = null;
        }

      return $result;
    }
    
     /**
     * Delete tutorial message 
     *
     * @param string $tutorial_id unique id for tutorial chat.
     *        (e.g. 'quiz-result')
     */
    public function deleteTutorial($tutorial_id = null) {
        $firestore =  $this->factory->createFirestore();
        $database =  $firestore->database();
        $citiesRef = $database->collection('messages')->orderBy('timestamp', 'asc');
        $documents = $citiesRef->documents();
         foreach ($documents as $message) {
            if ($message->exists()) {
                $data = $message->data();
                if($data['tutorial_id'] == $tutorial_id)
                {
                    $database->collection('messages')->document($message->id())->delete(); 
                }
                 
            }
        }
    }

    private function pushNotificationWrapper($topic, $title, $body,  $type = null, $main_quiz_id = null){
        $message = CloudMessage::withTarget('topic', $topic)->withNotification([
            'title' => $title,
            'body' => $body
        ])->withData(['type' =>  $type, 'main_quiz_id' => $main_quiz_id ]);
        return $message;
    }


    private function privatePushNotificationWrapper($data, $title, $body){
        $message = CloudMessage::fromArray([
            'token' => $data['firebase_token'],
            'data' => [
                'user_id' => $data['id'],
                'student_id' => $data['student_id']
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
    //sorting array helper function
    public function array_sort($time1, $time2)
        {
            if (strtotime($time1['timestamp']) < strtotime($time2['timestamp'])) {
                return -1;
            } elseif (strtotime($time1['timestamp']) > strtotime($time2['timestamp'])) {
                return 1;
            } else {
                return 0;
            }
        }
 
}