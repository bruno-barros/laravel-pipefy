<?php
return [

    /*
     * Token do usuário do Pipefy
     * Hoje estamos utilizando um token de usuario admin, mas o ideal seria um token a nivel de organização
     */
   'token' => env('PIPEFY_TOKEN',''),

    /*
     * Id do usuario
     */
    'user_id' => env('PIPEFY_USER_ID',''),

    /*
     * Id da organização
     */
    'org_id' => env('PIPEFY_ORGANIZATION_ID',''),

    /*
     * Ids dos pipes
     */
    'pipes_ids' => env('PIPEFY_PIPES_IDS',''),

    /*
     * Base url Pipefy
     */
    'baseUrl' => env('PIPEFY_BASEURL',''),

];