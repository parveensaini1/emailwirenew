<?php
App::uses('Component', 'Controller');
App::uses('ConnectionManager', 'Model');

class CrmComponent extends Component {

    private $accessToken;
    private $refreshToken;
    private $clientId = '1000.3S971TLC4K4YZZ5MGXUHGUE0K90ZRL';
    private $clientSecret = '3eb9d796c646ef05c133ca5b9f4a42599cc217e8ba';

    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->accessToken = '1000.b2edb52272c6791fea1a162e73d8bd60.ec371b3300686137f49ddd5d8c2ec571';
        $this->refreshToken = '1000.a2d3dddecad9039a41e442a57cfdd8b0.75c67ab4bd1a125d71793093d1c9cbb8';
        $this->db = ConnectionManager::getDataSource('default');
    }



    private function loadTokens() {
        $query = "SELECT * FROM zoho_api_tokens ORDER BY id DESC LIMIT 1";
        $result = $this->db->fetchAll($query);

        

        if (!empty($result)) {

            $this->accessToken = $result[0]['zoho_api_tokens']['access_token'];
            $this->refreshToken = $result[0]['zoho_api_tokens']['refresh_token'];
            $this->expiresAt = new DateTime($result[0]['zoho_api_tokens']['expires_at']);

            $now = new DateTime();
            $interval = $now->diff($this->expiresAt);
            $hoursDifference = $interval->h + ($interval->days * 24);

          

            if ($hoursDifference >= 1) {
                $this->refreshAccessToken();
            }

            return ['status' => true, 'message' => "Token get successfully"];

        } else {
            // Initialize with your current refresh token if no tokens in DB
            $this->refreshAccessToken();
        }
    }


    public function createRecord($data) {

        $token_res = $this->loadTokens();

        $isTokenGenerated = $token_res['status'] ?? false;

        if(!$isTokenGenerated){
            return ['status' => false,  "message" => "Token not generated."];
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.zohoapis.com/crm/v7/Leads',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessToken,
            ),
        ));

        $response = curl_exec($curl);

     

        curl_close($curl);


        $desc_resp = json_decode($response, true);


       

        if(isset($desc_resp['data'][0]['code'])){
           if($desc_resp['data'][0]['code'] == 'SUCCESS'){
                return ['status' => true, 'message' => "Data Added Successfully"];
           }
        }

        
        return ['status' => false,  "message" => "Data Not Added"];

        
    }


    private function saveTokens($accessToken, $refreshToken, $expiresIn) {
       
        $query = "INSERT INTO zoho_api_tokens (access_token, refresh_token, expires_at) VALUES (?, ?, ?)";
        $this->db->query($query, array($accessToken, $refreshToken, $expiresIn));

        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        // $this->expiresAt = $expiresIn;
    }

    public function refreshAccessToken() {
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token'
            ),
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $res_decode =  json_decode($response, true);


        if(isset($res_decode['access_token'])){
            $expirationTimeFormatted = date('Y-m-d H:i:s', time() + $res_decode['expires_in']);
            $this->saveTokens(
                $res_decode['access_token'],
                isset($res_decode['refresh_token']) ? $res_decode['refresh_token'] : $this->refreshToken,
                $expirationTimeFormatted
            );
            return ['status' => true, "message" => "Refreshed Access Token Successfully"];
        }else{
            return ['status' => false, "message" => "Failed to Refresh Access Token"];
        }
        
        
    }
}
?>
