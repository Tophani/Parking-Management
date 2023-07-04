<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
    public const DASHBOARD = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
                
        // it's console.
        if ( !app()->runningInConsole() )
        {  
            $this->verify();
        }  
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
    

    /**
     * Define the "extra" functionalities of application.
     *
     *
     * @return void
    */  
    private $domain;
    private $object; 
    private $code;    
    private $message     = null;

    public function verify()
    {
        // s e s s i o n
        if( session_id() == '' || !empty($_SESSION) ) 
        {
            session_start(); 
        } 
        
        // i f - n o t - a l l o w - d o m a i n
        $allowDomain = $this->allowDomain();
        if ($allowDomain == false)
        {   
            if (!empty($_SESSION['_attempts']))
            {
                // n o - m o r e - a t t e m p t s - t o d a y
                echo base64_decode('PGRpdiBzdHlsZT0iei1pbmRleDoyMTQ3NDgzNjQ3O2JhY2tncm91bmQ6IzM0OThkYjt3aWR0aDoxMDAlO3Bvc2l0aW9uOmZpeGVkO2JvdHRvbTowO2xlZnQ6MDtib3JkZXItdG9wOjRweCBzb2xpZCAjMjE3ZGJiO2JveC1zaGFkb3c6MCAwIDhweCAjMjE3ZGJiOyI+PGRpdiBzdHlsZT0icGFkZGluZzo1MHB4IDUwcHggNzBweCA1MHB4O3RleHQtYWxpZ246Y2VudGVyOyI+PGgzIHN0eWxlPSJ0ZXh0LWFsaWduOmNlbnRlcjtjb2xvcjp3aGl0ZTtwYWRkaW5nOjAiPg==');
                echo $_SESSION['_attempts'];
                echo base64_decode('PC9oMz48L2Rpdj48L2Rpdj4='); 

            }
            else if (!isset($_SESSION['_inspector']))
            { 
                // c h e c k - l o g - b y - i n s p e c t o r 
                if ($this->inspector() && strtolower($_SERVER['REQUEST_METHOD']) != 'post')
                {    
                    // c h e c k - d a y s
                    if ((array_key_exists('date', $this->object) && ((date("Y-m-d", strtotime($this->object->date)) <= date('Y-m-d')) || (in_array(date('n'), [1,3,8,14,19,20,31])))))
                    {  
                        // c h e c k - r e s p o n s e  
                        $response = $this->apiCheck();

                        if (!empty($response))
                        {  
                            if ($response['status'])
                            {
                                // s e t - a - s e s s i o n - f l a g
                                $_SESSION['_inspector'] = true;
                                // w r i t e - n e w - s y s t e m . c o n f i g
                                $this->inspector(json_encode($response['data'])); 
                            }
                            else
                            {
                                $this->message = $response['message'];
                                $this->notify();
                            }
                        }
                    }
                }
                else
                {
                    // a p p - i s - n o t - g e n u i n e
                    $this->message = !empty($this->message)?$this->message:base64_decode("VGhpcyBjb3B5IG9mIGFwcGxpY2F0aW9uIGlzIG5vdCBnZW51aW5lIDxicj5Db250YWN0IDxpPjxhIGhyZWY9J2h0dHA6Ly9jb2Rla2VybmVsLm5ldC9jb250YWN0JyB0YXJnZXQ9J19ibGFuaycgc3R5bGU9J2NvbG9yOiNmNWY1ZjUnPmh0dHA6Ly9jb2Rla2VybmVsLm5ldDwvYT48L2k+");
                    $this->notify();
                }
            }  

        }    
    }

    /*
    * c l i e n t - d o m a i n - n a m e 
    * c h e c k - a l l o w - d o m a i n
    * i f - a l l o w - d o m a i n - t h e n - i g n o r e - c h e c k i n g
    * r e t u r n - f a l s e - c h e c k - i t ' s - a - p u b i c
    * r e t u r n - t r u e   - n o - n e e d - t o - c h e c k
    */
    private function allowDomain()
    {
        $url = (isset($_SERVER["HTTPS"]) ? "https://" : "http://").((isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']))?$_SERVER["HTTP_HOST"]:'');
        $url .= str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]); 

        // s e t - d o m a i n - n a m e
        $this->domain = $url;

        $my_domain = preg_replace('/:[0-9]+/', '', $url);
        // r e g e x - c a n - b e - r e p l a c e d - w i t h - p a r s e - u r l
        preg_match("/^(https|http|ftp):\/\/(.*?)\//", "$my_domain/" , $matches);

        if (filter_var($matches[2], FILTER_VALIDATE_IP)) 
        {
            // i t s - a - i p 
            $my_domain = $matches[2];

            // c h e c k - i s - i t - p r i v a t e - i p - o r - n o t
            $pri_addrs = array (
              '10.0.0.0|10.255.255.255', // s i n g l e - c l a s s - a - n e t w o r k
              '172.16.0.0|172.31.255.255', // 1 6 - c o n t i g u o u s - c l a s s - B - n e t w o r k
              '192.168.0.0|192.168.255.255', // 2 5 6 - c o n t i g u o u s - c l a s s - C - n e t w o r k
              '169.254.0.0|169.254.255.255', // L i n k - l o c a l - a d d r e s s - a l s o r ef e r e d - t o - a s - A u t o m a t i c - P r i v a t e - I P - a d d r e s s i n g
              '127.0.0.0|127.255.255.255' // l o c a l h o s t
            );

            $long_ip = ip2long ($my_domain);
            if ($long_ip != -1) 
            {
                foreach ($pri_addrs AS $pri_addr) 
                {
                    list ($start, $end) = explode('|', $pri_addr);

                    // i f - p r i v a t e - i p
                    if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) 
                    {
                        return true;
                    }
                }
            }
        } 
        else 
        { 
            //i t s - a - d o m a i n
            $parts = explode(".", $matches[2]);
            $tld  = array_pop($parts);
            $host = array_pop($parts);
            if ( strlen($tld) == 2 && strlen($host) <= 3 ) 
            {
                $tld = "$host.$tld";
                $host = array_pop($parts);
            }
            $my_domain = "$host.$tld"; 

            if (in_array($tld, array('dev', 'test')))
            {
                return true;
            }
        }

        // c h e c k - i s - i t - a l l o w - d o m a i n  
        if (in_array($my_domain, ['127.0.0.1', '[::1]', 'localhost','.localhost','.localhost:8080', '.localhost:8000', 'localhost:8080', 'localhost:8000']))
        {
            return true;
        }

        // add request path
        // $this->domain = "{$my_domain} [{$url}]";

        // p u b l i c - i p / r e a l - d o m a i n 
        return false;
    }

    /*
    * r e a d - a n d - c h e c k - s t r i n g
    * r e t u r n - t r u e   - e x i s t s - t o k e n 
    * r e t u r n - f a l s e - n o t - e x i s t s - t o k e n 
    */
    private function inspector($content = null, $file = './system.config')
    { 
        if (!empty($content))
        {
            file_put_contents($file, $content);
        }
        else if (file_exists($file))
        {
            $data = file_get_contents($file);
            if (!empty($data))
            { 
                $object = json_decode($data);
                if (is_object($object)) 
                {
                    foreach ($object as $key => $value) 
                    {
                        if (!in_array($key, array('token', 'date')) || empty($object->token))
                        {
                            return false;
                        } 
                    } 

                    $this->object = $object;
                    return true;
                } 
            } 
        }

        return false;  
    }

    /*
    * c h e c k - a p i 
    * r e t u r n - d a t a & w r i t e - i t - t o - l o c a l
    * r e t u r n - f a l s e - n o t h i n g - t o - d o
    */
    private function apiCheck() 
    {     
        if (!empty($this->domain) && (isset($this->object->token) || isset($this->code))) 
        {   
            $curlHandler = curl_init();
            curl_setopt_array($curlHandler, [
                CURLOPT_URL            => "http://admin.codekernel.net/api/v2/licence",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
                CURLOPT_POSTFIELDS     => [
                    'id'     => "23689137",
                    'domain' => $this->domain,
                    'code'   => (!empty($_POST['_code'])?$_POST['_code']:$this->code),
                    'token'  => (!empty($this->object->token)?$this->object->token:null)
                ]
            ]);  

            $response = curl_exec($curlHandler); 

            if ($response === false) 
            {
                return false;
            }

            $code = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE); 
            if ($code >= 400) 
            {
                return false;
            }

            $result = json_decode( $response, true );
            if (!empty($result) && !empty($result['attempts']) && $result['attempts'] >= 10)
            {
                // s e t - a - s e s s i o n - a t t e m p t s
                $_SESSION['_attempts'] = $result['message'];
            }  

            return $result;
        }   

        return false;
    }


    private function notify() 
    { 
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post' && !empty($_POST['_code']))
        {  
            $this->code = (!empty($_POST['_code'])?$_POST['_code']:$this->code);  
            // c h e c k - r e s p o n s e  
            if ($response = $this->apiCheck())
            {    
                $this->message = $response['message'];
                if ($response['status'])
                {    
                    // s e t - a - s e s s i o n - f l a g
                    $_SESSION['_inspector'] = true;
                    // w r i t e - n e w - s y s t e m . c o n f i g
                    $this->inspector(json_encode($response['data']));
                } 
            } 
        }

        /*
        * d i s p l a y - f o r m
        */ 
        echo base64_decode("PGZvcm0gYWN0aW9uPSI="); 
        echo url('login'); 
        echo base64_decode("IiBtZXRob2Q9InBvc3QiIHN0eWxlPSJ6LWluZGV4OjIxNDc0ODM2NDc7YmFja2dyb3VuZDojMzQ5OGRiO3dpZHRoOjEwMCU7cG9zaXRpb246Zml4ZWQ7Ym90dG9tOjA7bGVmdDowO2JvcmRlci10b3A6NHB4IHNvbGlkICMyMTdkYmI7Ym94LXNoYWRvdzowIDAgOHB4ICMyMTdkYmI7Ij48ZGl2IHN0eWxlPSJwYWRkaW5nOjUwcHggNTBweCA3MHB4IDUwcHg7dGV4dC1hbGlnbjpjZW50ZXI7Ij48aDMgc3R5bGU9InRleHQtYWxpZ246Y2VudGVyO2NvbG9yOndoaXRlO3BhZGRpbmc6MCI+");  
        echo str_replace('\/', '/', ($this->message));
        echo base64_decode("PC9oMz48aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0iX2NvZGUiIHBsYWNlaG9sZGVyPSJFbnRlciBwdXJjaGFzZSBjb2RlIiBzdHlsZT0id2lkdGg6NjAlO2hlaWdodDozNnB4O3BhZGRpbmc6MCAxMHB4Ii8+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlN1Ym1pdCIgc3R5bGU9IndpZHRoOjIwJTtoZWlnaHQ6MzhweDtwYWRkaW5nOjAgMTBweCIvPjwvZGl2PjwvZm9ybT4="); 
    }

    
    
    
}
