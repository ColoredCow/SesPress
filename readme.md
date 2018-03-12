![sespress-min](https://user-images.githubusercontent.com/12053186/37273426-d000bde4-25ff-11e8-954e-b2be52a83fb0.png)

## About

WordPress plugin to send emails using Amazon's Simple Email Service.

## Installation

1. Clone or download the repository in your project's plugins folder
```sh
git clone https://github.com/coloredcow/sespress.git
```
2. Install dependencies via composer install
```sh
cd wp-content/plugins/sespress
composer install
```
3. Activate SesPress plugin from the WordPress Admin Dashboard
4. Once activated, go to menu `Settings > SesPress`. Enter your AWS key ID, secret key and region to confirm credentials.

## Usage

Add the following snippet at the end of your active theme's `functions.php`. Change the recipients and sender name and email accordingly.

**Note**: This will change your site's behavior.

```php
add_action( 'wp', 'sespress_send_sample' );
function sespress_send_sample() {

    $args = [
        'subject' => 'Welcome to SesPress',
        'recipients' => [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
            ],
        ],
        'sender' => [
            'name' => 'Admin',
            'email' => 'admin@mysite.com',
        ],
        'message' => [
            'html' => '<h2>Test message embedded in HTML tags.</h2>',
        ]
    ];
    $sespress = new SesPress;
    $result = $sespress->send( $args );
    wp_die( $result['data'] );
}
```
