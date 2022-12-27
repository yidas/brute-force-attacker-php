<p align="center">
    <h1 align="center">Brute Force Attacker <i>for</i> PHP</h1>
    <br>
</p>

Brute-force attack tool for generating all possible string and executing function

[![Latest Stable Version](https://poser.pugx.org/yidas/google-maps-services/v/stable?format=flat-square)](https://packagist.org/packages/yidas/brute-force-attacker)
[![License](https://poser.pugx.org/yidas/google-maps-services/license?format=flat-square)](https://packagist.org/packages/yidas/brute-force-attacker)

OUTLINE
-------

- [Demonstration](#demonstration)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Options](#options)

---

DEMONSTRATION
-------------

```php
\yidas\BruteForceAttacker::run([
    'length' => 2,
    'callback' => function ($string) {
        echo "{$string} ";
    },
]);

/* Result
AA AB AC AD AE AF AG AH AI AJ AK AL AM AN AO AP AQ AR AS AT AU AV AW AX AY AZ Aa Ab Ac Ad Ae Af Ag Ah Ai Aj Ak Al Am An Ao Ap Aq Ar As At Au Av Aw Ax Ay Az A0 A1 A2 A3 A4 A5 A6 A7 A8 A9 BA ...
*/
```

Generates `0`-`9` string and matches target string:

```php
\yidas\BruteForceAttacker::run([
    'length' => 6,
    'charMap' => range('0', '9'),
    'callback' => function ($string, $count) {
        if ($string=="264508") {
            echo "Matched `{$string}` with {$count} times\n";
            return true;
        }
    },
]);
```

---

REQUIREMENTS
------------
This library requires the following:

- PHP 5.4.0+\|7.0+

---

INSTALLATION
------------

Run Composer in your project:

    composer require yidas/brute-force-attacker
    
Then you could call it after Composer is loaded depended on your PHP framework:

```php
require __DIR__ . '/vendor/autoload.php';

use yidas\BruteForceAttacker;
```

---

USAGE
-----

Call the `run()` static method and bring in the options to start:

```php
\yidas\BruteForceAttacker::run(array $options)
```

### Options

Setting all options including skip mechanism:

```php
$hash = '5b7476628919d2d57965e25ba8b2588e94723b76';

\yidas\BruteForceAttacker::run([
    'length' => 8,
    'charMap' => array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'), ["+", "/"]),
    'callback' => function ($key, & $count) use ($hash) {
        if (sha1($key) == $hash) {
            echo "Matched `{$key}` | Hash: {$hash}\n";
            exit;
        }
        // Display key every some times
        if ($count == 0 || $count > 10000000) {
            echo "{$key} | " . date("H:i:s") . "\n";
            $count = 0;
        }
    },
    'skipLength' => 8,  // Select 8st character for skipCount
    'skipCount' => 1,   // Start from `B` (Skip 1 arrangement form charMap)
]);
```

#### length

String length for generating

#### charMap

Character map used to generate strings

#### Callback

Customized function for performing brute-force attack

```php
function (string $key, integer & $count)
```

#### skipLength

String length for skipping based on `skipCount` setting

#### skipCount

Skip count of the `charMap` based on `skipLength`
