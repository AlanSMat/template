<?php
namespace App\Assets\Inc\Classes;

use Dotenv\Dotenv;
use TheNetworg\OAuth2\Client\Provider\Azure;
use App\Assets\Inc\Classes\UsersADInfo;
use App\Assets\Inc\Classes\Session\Session;

class SSOValidation
{
    public $provider;
    public $redirectToAuthorizationPage = true;

    public function __construct()
    {   
        $this->loadEnvFile();
        $this->provider = $this->loadAzureProvider();    
    }
    
    private function loadEnvFile()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        switch($_SERVER['SERVER_NAME']) 
        {
            case 'localhost' :
                $_ENV['REDIRECT_URI'] = 'http://localhost:8888/site/sfs-core/app/index.php';
                break;
        }
    }

    private function loadAzureProvider()
    {
        $provider = new Azure([
            'tenant'       => $_ENV['TENANT'],
            'clientId'     => $_ENV['CLIENT_ID'],
            'clientSecret' => $_ENV['CLIENT_SECRET'],
            'redirectUri'  => $_ENV['REDIRECT_URI'],
            'proxy'        => 'proxy.det.nsw.edu.au:80',
        ]);

        // Set to use v2 API, skip the line or set the value to Azure::ENDPOINT_VERSION_1_0 if willing to use v1 API
        $provider->defaultEndPointVersion = Azure::ENDPOINT_VERSION_2_0;

        $baseGraphUri = $provider->getRootMicrosoftGraphUri(null);
        $provider->scope = 'openid profile email offline_access ' . $baseGraphUri . '/User.Read';

        return $provider;
    }
    
    /** 
     *  13/02/2023 - function authorize()
     *  Description: Checks to see if $_GET variables code and state as well as the $_SESSION variable OAuth2.state are set, if they are, 
     *               then it will try to get an access token (using the authorization code grant) and then it will try to get the users Active Directory information
     *  
     *  @return void
     */
    public function authorize() 
    {
        // This is the endpoint we need to send to, so that we can get access to the departments Active Directory information
        $activeDirectoryUri = $_ENV['ACTIVE_DIRECTORY_URI'];    
    
        // API key
        $activeDirectoryAPI = $_ENV['ACTIVE_DIRECTORY_API'];
        
        $this->redirectToAuthorizationPage = false;

        // set the scopes, which will be passed into the token
        $this->provider->scope = 'openid profile offline_access ' . $activeDirectoryAPI;
    
        if (isset($_GET['code']) && Session::has('OAuth2.state') && isset($_GET['state'])) 
        {    
            if ($_GET['state'] == Session::get('OAuth2.state')) 
            {
                Session::unset('OAuth2.state');
                              
                // Try to get an access token (using the authorization code grant)                
                $token = $this->provider->getAccessToken('authorization_code', [
                    'scope' => $this->provider->scope,
                    'code'  => $_GET['code']
                ]);
                
                Session::set('token', $token);
                Session::set('access_token', $token->getToken());

                // 13/02/2023 - Get the users Active Directory information
                $usersADInfo = new UsersADInfo($token->getToken(), $activeDirectoryUri);
                              
                                
            } else {
                return null;
            }
        } 
    }

    public function isTokenValid() 
    {
        // token has been set
        if(Session::has('token'))
        {
            // Check local server's session data for a token
            // and verify if still valid 
            $token = Session::get('token');

            if($token->hasExpired()) 
            {
                if (!is_null($token->getRefreshToken())) 
                {                       
                    $token = $this->provider->getAccessToken('refresh_token', [
                                     'scope' => $this->provider->scope,
                                     'refresh_token' => $token->getRefreshToken()
                             ]);                
                    
                    // copy the refreshed token to the session
                    Session::set('token', $token);
                    
                    // get the access token only
                    Session::set('access_token', $token->getToken());
                    
                    if($this->redirectToAuthorizationPage) 
                    {
                        header('Location: index.php');
                    }
                }
            };      
        };
        
        // If the token is not found send to the login page
        if (!Session::has('token')) {
    
            $authorizationUrl = $this->provider->getAuthorizationUrl(['scope' => $this->provider->scope]);
            
            Session::set('OAuth2.state', $this->provider->getState());
    
            header('Location: ' . $authorizationUrl);
        }
    }

    public function logout() 
    {
        // Clear the session
        Session::clear();
        Session::remove();        
    }
}