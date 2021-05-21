<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

include __DIR__ . '/include/comon.php';
include __DIR__ . '/include/config.php';

function gridpane_MetaData()
{
    return array(
        'DisplayName' => 'Gridpane',
        'APIVersion' => '1.0.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '1111', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '1112', // Default SSL Connection Port
        'ServiceSingleSignOnLabel' => 'Login to Panel as User',
        'AdminSingleSignOnLabel' => 'Login to Panel as Admin',
        // 'ListAccountsUniqueIdentifierDisplayName'    => 'Domain',
        // 'ListAccountsUniqueIdentifierField'    => 'domain'
    );
}

// Service Options
function gridpane_ConfigOptions()
{

    return array(

        // PHP Version
        'PHP Version' => array(
            'Type' => 'radio',
            'Options' => '7.4,7.3,7.2',
            'Description' => 'Select a PHP Version.',
            'Default' => '7.4',
        ),
        // Nginx Caching
        'Server Caching' => array(
            'Type' => 'radio',
            'Options' => 'redis,fastcgi,none',
            'Description' => 'Select your server-side cache.',
            'Default' => 'redis',
        ),
        // WAF
        'Web Application Firewall (WAF)' => array(
            'Type' => 'radio',
            'Options' => '6G,7G,modsec,none',
            'Description' => 'Select a WAF.',
            'Default' => '7G',
        ),
        // SMTP Selection
        'SMTP Integration' => array(
            'Type' => 'radio',
            'Options' => 'ours,sendgrid',
            'Description' => 'Use the integrated SendGrid SMTP Provider?',
            'Default' => 'ours',
        ),
        // SMTP Selection
        'DNS API Integration' => array(
            'Type' => 'radio',
            'Options' => 'dnsme_full,dnsme_challenge,cloudflare_full,cloudflare_challenge,none',
            'Description' => 'Select the DNS provider.',
            'Default' => 'dnsme_full',
        ),
        // SMTP Selection
        'PHP Process Management' => array(
            'Type' => 'radio',
            'Options' => 'dynamic,static,ondemand',
            'Description' => 'Configure your PHP-FPM.',
            'Default' => 'static',
        )
        //
    );
}
// Create Site
function gridpane_CreateAccount(array $params)
{
    try {
        // Define Params
        $domain = $params['domain'];

        // Run Create Module
        // $f2 = fopen(__DIR__.'/params.txt', 'w');
        // fwrite($f2, print_r($params, true));

        $url = $params['domain'];
        //$email = $params['email'];
        $server_id = $params['customfields']['Select Server'];
        $server_id = intval($server_id);
        $username = $params['username'];
        $password = $params['password'];
        $php_version = $params['configoption1'];
        $nginx_caching = $params['configoption2'];
        $waf = $params['configoption3'];
        $smtp = $params['configoption4'];
        $dns_management = $params['configoption5'];
        $pm = $params['configoption6'];

        if (!empty($url) && !empty($server_id)) {
            $data = array();
            $s_user = array();
            if (!empty($username)) {
                $user = array();
                $user['username'] = $username;
                $user['server_id'] = $server_id;
                $user['password'] = $password;

                // $s_user = add_system_user($user);
                if (!empty($username)) {
                    // $data['system_user_id'] = intval($s_user->id);
                    //$data['username'] = $c->customfields4;
                    $data['username'] = $username;
                }

            }

            $data['url'] = $url;
            $data['server_id'] = $server_id;
            $data['php_version'] = $php_version;
            $data['pm'] = $pm;
            $data['nginx_caching'] = $nginx_caching;
            $data['waf'] = $waf;
            $data['smtp'] = $smtp;
            $data['dns_management'] = $dns_management;

            // $wpusers = json_decode($c->customfields11);
            // if(!empty($wpusers)) {
            //   $data['wp_users'] = $wpusers;
            // }
            $gridpane_responce = create_site($data);

            //    $f4 = fopen(__DIR__.'/gridpane_responce.txt', 'a');
            // fwrite($f4, print_r($gridpane_responce, true));
        }

    } catch (Exception $e) {
        // Log error in module log
        logModuleCall(
            'gridpane',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
        return $e->getMessage();
    }

    return 'success';
}

function gridpane_TerminateAccount(array $params)
{
    try {
        // $f2 = fopen(__DIR__.'/Terminate.txt', 'a');
        $domain = $params['domain'];
        $site_list = get_site_list();
        // fwrite($f2, print_r($site_list, true));
        if (!empty($site_list->data)) {
            foreach ($site_list->data as $k => $v) {
                $id = $v->id;
                $url = $v->url;
                $system_user_id = $v->system_user_id;
                if ($domain == $url) {
                    delete_site($id);
                    $r = delete_system_user($system_user_id);
                }
            }
        }
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'gridpane',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function gridpane_ClientArea($vars)
{
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';

    if ($requestedAction == 'manage') {
        $serviceAction = 'get_usage';
        $templateFile = 'templates/manage.tpl';
    } else {
        $serviceAction = 'get_stats';
        $templateFile = 'templates/overview.tpl';
    }

    try {
        // Call the service's function based on the request action, using the
        // values provided by WHMCS in `$params`.
        $response = array();

        $extraVariable1 = 'abc';
        $extraVariable2 = '123';

        return array(
            'tabOverviewReplacementTemplate' => $templateFile,
            'templateVariables' => array(
                'extraVariable1' => $extraVariable1,
                'extraVariable2' => $extraVariable2,
            ),
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'gridpane',
            __FUNCTION__,
            $vars,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}

function gridpane_TestConnection(array $params)
{
    try {

        $response_array = testConnection($params['serveraccesshash']);

        $success = (is_array($response_array) && (count($response_array) > 1));
        $errorMsg = $response_array;
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'gridpane',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}
