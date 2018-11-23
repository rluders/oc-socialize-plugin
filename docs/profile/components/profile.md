# Profile Component

This component allows you to push the user's object to the page. So you can use it as you want.

## Properties

You can get all the properties for the model, as you want.
Remeber it's an Eloquent object, so, you will be able to work with it as you wish.
Here is a short list of the common properties that you may whant to use.

### ID

Display the user ID.

`{{ socialize.user.id }}`

### Username

Display the username.

`{{ socialize.user.username }}`

> By default the Rainlab.User determines that your username is your email. But you can change it on settings.

### Name

Display the user firstname.

`{{ socialize.user.name }}`

### Surname

Display the user lastname.

{{ socialize.user.surname }}

### Email

Display the user e-mail address.

{{ socialize.user.email }}

### Last login

Display the user last login date and format it.

{{ socialize.user.last_login|date("F jS \\a\\t g:ia") }}

> You always need to format any date to display it.

## Example

Here is an very basic example that shows you how to display the user data.

```twig
{% if socialize.user %}

<h1>{{ socialize.user.name }}'s Profile</h1>

<p><strong>Username:</strong> {{ socialize.user.username }}</p>
<p><strong>Firstname:</strong> {{ socialize.user.name }}</p>
<p><strong>Surname:</strong> {{ socialize.user.surname }}</p>
<p><strong>E-mail:</strong> {{ socialize.user.email }}</p>
<p><strong>Last login:</strong> {{ socialize.user.last_login|date("F jS \\a\\t g:ia") }}</p>

<h2>User dump</h2>
<code>
{{ socialize.user }}
</code>

{% else %}

<h1>User not found</h1>

{% endif %}
```