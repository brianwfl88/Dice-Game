# Dice-Game
A game of rolling dice


## How to play:

### To start a game:
```php
$game = new Dice();

$game->players = ['A', 'B', 'C', 'D'];

$game->start();
```

### To get results:
```php
$game->print_result();
```

Players that are highlighted with green are the winner!
