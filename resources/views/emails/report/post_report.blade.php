@php
    /**
    * @var $title string
    * @var $post_url string
    * @var $post_title string
    * @var $comment string
    * @var $reporter_username string
    * @var $reporter_id int
    * @var $reported_user_username string
    * @var $reported_user_id int
    * @var $reason string
    */
@endphp

@component('mail::message')
# {{ $title }}

@component('mail::button', ['url' => $post_url])
    Zum gemeldeten Beitrag
@endcomponent

Einzelheiten:

- Titel des Beitrags: {{ $post_title }}
@isset($comment)
- Kommentar: {!! $comment !!}
@endisset
- Meldender Benutzer: {{ sprintf("%s (%s)", $reporter_username, $reporter_id) }}
- Gemeldeter Benutzer: {{ sprintf("%s (%s)", $reported_user_username, $reported_user_id) }}
- Text aus dem Eingabefeld: {{ $reason }}
@endcomponent
