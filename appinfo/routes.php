<?php

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\TypescriptReduxReactBoilerplate\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [

        //INDEX
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],

        // ANONYMIZATION
        ['name' => 'anonymization#getData', 'url' => '/getdata', 'verb' => 'GET'],
        ['name' => 'anonymization#getMdText', 'url' => '/getmdtext', 'verb' => 'GET'],
        ['name' => 'anonymization#anonymize', 'url' => '/anonymize', 'verb' => 'POST'],

        // 1. SENSITIVE DATA
        /* ['name' => 'sensitiveData#index', 'url' => '/sensitivedatalist', 'verb' => 'GET'],
        ['name' => 'sensitiveData#show', 'url' => '/sensitivedatashow/', 'verb' => 'GET'],
        ['name' => 'sensitiveData#showByData', 'url' => '/sensitivedatashowbydata/', 'verb' => 'GET'],
        ['name' => 'sensitiveData#insert', 'url' => '/sensitivedatainsert', 'verb' => 'POST'],
        ['name' => 'sensitiveData#update', 'url' => '/sensitivedataupdate', 'verb' => 'PUT'],
        ['name' => 'sensitiveData#delete', 'url' => '/sensitivedatadelete', 'verb' => 'DELETE'], */

        // 2. METHODS
        /* ['name' => 'methods#index', 'url' => '/methodslist', 'verb' => 'GET'],
        ['name' => 'methods#show', 'url' => '/methodsshow/', 'verb' => 'GET'],
        ['name' => 'methods#showByDesc', 'url' => '/methodsshowbydesc/', 'verb' => 'GET'],
        ['name' => 'methods#insert', 'url' => '/methodsinsert', 'verb' => 'POST'],
        ['name' => 'methods#update', 'url' => '/methodsupdate', 'verb' => 'PUT'],
        ['name' => 'methods#delete', 'url' => '/methodsdelete', 'verb' => 'DELETE'], */

        // 3. METHODSDATA
        /* ['name' => 'methodsData#index', 'url' => '/methodsdatalist', 'verb' => 'GET'],
        ['name' => 'methodsData#indexJoin', 'url' => '/methodsdatalistjoin', 'verb' => 'GET'],
        ['name' => 'methodsData#show', 'url' => '/methodsdatashow', 'verb' => 'GET'],
        ['name' => 'methodsData#showByIdDataAndMethods', 'url' => '/methodsdatashowdatamethods', 'verb' => 'GET'],
        ['name' => 'methodsData#insert', 'url' => '/methodsdatainsert', 'verb' => 'POST'],
        ['name' => 'methodsData#update', 'url' => '/methodsdataupdate', 'verb' => 'PUT'],
        ['name' => 'methodsData#delete', 'url' => '/methodsdatadelete', 'verb' => 'DELETE'], */ 

        // 4. TAG
        ['name' => 'tag#index', 'url' => '/taglist', 'verb' => 'GET'],
        /* ['name' => 'tag#show', 'url' => '/tagshow/', 'verb' => 'GET'],
        ['name' => 'tag#showByLabel', 'url' => '/tagshowbylabel/', 'verb' => 'GET'],
        ['name' => 'tag#insert', 'url' => '/taginsert', 'verb' => 'POST'],
        ['name' => 'tag#update', 'url' => '/tagupdate', 'verb' => 'PUT'],
        ['name' => 'tag#delete', 'url' => '/tagdelete', 'verb' => 'DELETE'], */

        
        
    ]
];
