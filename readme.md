# SesPress

WordPress plugin to send emails from Amazon's Simple Email Service.

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
2. Enable plugin from the WordPress Admin Dashboard
3. Go to menu `Settings > SesPress`
4. Enter your AWS key ID, secret key and region to confirm credentials.

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
				'name' => 'Your Name',
				'email' => 'yourname@example.com',
			]
		],
		'sender' => [
			'name' => 'Admin',
			'email' => 'admin@example.com',
		],
		'message' => [
			'html' => '<h2>Some test message embedded in HTML tags.</h2>',
		]
	];
	$result = new Ses_Press::send_mail( $args );
	wp_die($result['data']);
}
```
