Google Voice Dialer
===================

This is a basic API for use with Google Voice (until they release a real one).

Basic Usage
-----------

Dialing a number is simple

    	$gv = new GoogleVoice($user_email, $user_password);
    	$gv->call($your_phone_to_connect, $their_phone);

Sending a text is easy

    	$gv->sms($number, $message);

License
-------

Creative Commons BY-ND-NC (Attribution, Non-Commercial, No Derivites) [for the time being]

Original code is released under the MIT Open Source License. Feel free to do whatever you want with [the original code].
