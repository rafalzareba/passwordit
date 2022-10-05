<?php

return [
    'showScriptName' => false,
    'enablePrettyUrl' => true,
    'rules' => [
        '' => 'site/index',
        '<controller:[-\w ]+>/<id:[-\d ]+>' => '<controller>/view',
        '<controller:[-\w ]+>/<action:[-\w ]+>/<id:[-\d ]+>' => '<controller>/<action>',
        '<alias:index|login|contact>' => 'site/<alias>',
    ],
];
