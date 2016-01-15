<!DOCTYPE html>
<html>
    <head>
        <title>COMP 4711 Lab 1</title>
    </head>
    <body>
    <?php

    $board = (isset($_GET["board"])) ? $_GET["board"] : null;
    $game = new Game($board);
    $game->run();
    ?>
    </body>
</html>

<?php

class Game {
    var $position;

    // construct the game object
    public function __construct($board) {
        // check to see if the passed in board is empty or invalid
        // if invalid, pad with - until of length 9
        if (empty($board) || strlen($board) < 9) {
            $this->position = str_pad($board, 9, "-");
            // split into an array
            $this->position = str_split($this->position);
        } else {
            $this->position = str_split($board);
        }

    }

    // game loop
    public function run() {
        $msg = null;

        if ($this->winner("x")) {
            $msg = "You Won!";
        } else if ($this->tied()) {
            $msg = "Tie Game!";
        } else {
            $this->move();

            if ($this->winner("o")) {
                $msg = "You Lose!";
            }
        }
        echo $msg;
        $this->display();
    }

    // display the game board
    public function display() {
        echo "<table cols=”3” style=”font­size:large; font­weight:bold”>";
        echo "<tr>"; // open the first row
        for ($pos = 0; $pos < 9; $pos++) {
            echo $this->show_cell($pos);
            if ($pos % 3 == 2) echo "</tr><tr>"; // start a new row for the next square
        }
        echo "</tr>"; // close the last row
        echo "</table>";
        echo "<br><br><a href='?'>New Game</a>";
    }
    // get the appropriate character to display on the game board, else return a hyphen
    private function show_cell($cell) {
        $token = $this->position[$cell];

        if ($token != "-") {
            return "<td>" . $token . "</td>";
        }

        $this->newposition = $this->position;
        $this->newposition[$cell] = "x";

        $move = implode($this->newposition);
        $link = "/comp4711-tictactoe/index.php?board=" . $move;

        return "<td><a href='$link'>-</a></td>";
    }

    // check if the game tied
    private function tied() {
        for ($i = 0; $i < 9; $i++) {
            if ($this->position[$i] == "-") {
                return false;
            }
        }
        return true;
    }

    private function move() {
        $done = false;

        while (!$done) {
            $rand = rand(0, 8);
            if ($this->position[$rand] == "-") {
                $this->position[$rand] = "o";
                $done = true;
            }
        }
    }
    // tic tac toe logic for finding a winner
    public function winner($token) {
        // rows
        for ($row = 0; $row < 3; $row++) {
            if (($this->position[3*$row]    == $token) &&
                ($this->position[3*$row+1]  == $token) &&
                ($this->position[3*$row+2]  == $token)) {
                return true;
            }
        }
        // cols
        for ($col = 0; $col < 3; $col++) {
            if (($this->position[$col]      == $token) &&
                ($this->position[$col+3]    == $token) &&
                ($this->position[$col+6]    == $token)) {
                return true;
            }
        }
        // diag 1
        if (($this->position[0] == $token) &&
            ($this->position[4] == $token) &&
            ($this->position[8] == $token)) {
            return true;
        }
        // diag 2
        if (($this->position[2] == $token) &&
            ($this->position[4] == $token) &&
            ($this->position[6] == $token)) {
            return true;
        }

        return false;
    }
}

?>