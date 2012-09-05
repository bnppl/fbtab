<?php
namespace Traffic\Silex\FBTab;

class FacebookUtils 
{
    protected $signed_request;
            
    public function __construct($signed_request = null) 
    {
        $this->signed_request = $signed_request;
    }
    
    public function getSignedRequest(){
        return $this->signed_request;
    }


    public function isPageLiked()
    {
        
        $signedRequest = $this->getSignedRequest();
        if(!$signedRequest)
        {
        if (isset($_REQUEST['signed_request']))
        {
            $data = $_REQUEST['signed_request'];
            $encoded_sig = null;
            $payload = null;
            list($encoded_sig, $payload) = explode('.', $data, 2);
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));

            $is_fan = isset($data->page->liked)? $data->page->liked : '';
            return $is_fan;
        }
        }


        if(isset($signedRequest['page']) && isset($signedRequest['page']['liked']) && $signedRequest['page']['liked'] ==true)
        {
        return true;
        }
        return false;
    }
}