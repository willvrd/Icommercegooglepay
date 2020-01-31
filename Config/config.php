<?php

return [
    'name' => 'Icommercegooglepay',

    /**
     * Card networks supported
     */
    'allowedCards' => ["AMEX", "DISCOVER", "MASTERCARD", "VISA","JCB","INTERAC"],


    /**
     * Card authentication methods supported
     */
    'allowedCardsAuth' => ["PAN_ONLY", "CRYPTOGRAM_3DS"],
     

];
