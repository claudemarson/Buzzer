<?php

namespace Buzzer;

require_once dirname(__DIR__) . '/config.php';

/**
 * Access and manipulate the Buzzer-database
 */
class Database extends \mysqli
{
    /**
     * Open a new connection to the Buzzer-database
     */
    public function __construct()
    {
        parent::__construct(DB_HOST, DB_USERNAME, DB_PASSWORD, 'buzzer');
    }
    
    /**
     * Check whether a team ID is valid
     * @param int $teamID the team's ID
     * @return bool
     */
    public function getIsValidTeamID(int $teamID): bool
    {
        $isValid = false;
        
        $stmt = $this->prepare(
            'SELECT COUNT(ID)=1 AS valid '
            . 'FROM team '
            . 'WHERE ID=?');
        $stmt->bind_param('i', $teamID);
        $stmt->execute();
        $stmt->bind_result($isValid);
        $stmt->fetch();
        $stmt->close();
        
        return $isValid;
    }
    
    /**
     * Set the team name
     * @param string $teamName the new team name
     * @param string $passcode the passcode given to the team
     * @return string the TeamID if the passcode is valid and the team is set,
     * empty string otherwise
     */
    public function setTeamName(string $teamName, string $passcode): string
    {
        $teamID = null;
        
        $fetchStmt = $this->prepare(
            'SELECT ID '
            . 'FROM team '
            . 'WHERE passcode=?');
        $fetchStmt->bind_param('s', $passcode);
        $fetchStmt->execute();
        $fetchStmt->bind_result($teamID);
        $fetchStmt->fetch();
        $fetchStmt->close();
        
        if ($teamID !== null) {
            $updateStmt = $this->prepare(
                'UPDATE team '
                . 'SET teamName=? '
                . 'WHERE ID=?');
            $updateStmt->bind_param('ss', $teamName, $teamID);
            $updateStmt->execute();
            $updateStmt->close();
            
            return $teamID;
        }
        
        return '';
    }
    
    /**
     * Return all yet undisplayed answers in an array
     * @return string[] array of array with keys 'teamName' and 'answer'
     */
    public function getAnswers(): array
    {
        $gameroundID = null;
        $answer = null;
        $teamName = null;
        $newAnswer = false;
        $message = [];
        
        do {
            $fetchStmt = $this->prepare(
                'SELECT gameround.ID, answer, teamName '
                . 'FROM gameround INNER JOIN team '
                . '  ON gameround.team=team.ID '
                . 'WHERE NOT displayed');
            $fetchStmt->execute();
            $fetchStmt->bind_result($gameroundID, $answer, $teamName);
            $newAnswer = $fetchStmt->fetch();
            $fetchStmt->close();
            
            if ($newAnswer === true) {
                $updateStmt = $this->prepare(
                    'UPDATE gameround '
                    . 'SET displayed=1 '
                    . 'WHERE ID=?');
                $updateStmt->bind_param('i', $gameroundID);
                $updateStmt->execute();
                $updateStmt->close();

                $message[] = ['teamName' => "$teamName", 'answer' => "$answer"];
            }
        } while ($newAnswer === true);
        
        return $message;
    }
    
    /**
     * Set the team's answer
     * @param string $teamID the team's ID
     * @param string $answer the team answer
     */
    public function setAnswer(string $teamID, string $answer)
    {
        $stmt = $this->prepare(
            'INSERT IGNORE INTO gameround (team, answer) '
            . 'VALUES (?, ?)');
        $stmt->bind_param('ss', $teamID, $answer);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Resets the game and clears the screen
     */
    public function resetGameTurn()
    {
        $this->query('TRUNCATE TABLE gameround');
        $this->setClearScreen(true);
    }
    
    /**
     * Get the visibility of the answers on the screen
     * @return int 1: hidden, 2: revealed
     */
    public function getScreenAnswerVisibility(): int
    {
        return (int) $this->query(
            "SELECT setting "
            . "FROM config "
            . "WHERE ID = 'RevealAnswers'")
            ->fetch_assoc()['setting'];
    }
    
    /**
     * Set the visibility of the answers on the screen
     * @param int $reveal 0: no action, 1: hide, 2: reveal
     */
    public function setScreenAnswerVisibility(int $reveal)
    {
        if (($reveal >= 0) && ($reveal <= 2)) {
            $stmt = $this->prepare(
                "UPDATE config "
                . "SET setting=? "
                . "WHERE ID = 'RevealAnswers'");
            $stmt->bind_param('i', $reveal);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    /**
     * Get the flag for clearing the screen
     * @return bool true: clear the screen, false: no action
     */
    public function getClearScreen(): bool
    {
        return $this->query(
            "SELECT setting "
            . "FROM config "
            . "WHERE ID = 'ClearScreen'")
            ->fetch_assoc()['setting'];
    }
    
    /**
     * Set or unset the flag for clearing the screen
     * @param bool $clearScreen true: clear the screen, false: no action
     */
    public function setClearScreen(bool $clearScreen)
    {
        $stmt = $this->prepare(
            "UPDATE config "
            . "SET setting=? "
            . "WHERE ID = 'ClearScreen'");
        $stmt->bind_param('i', $clearScreen);
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Get the current buzzer type
     * @return int
     */
    public function getCurrentBuzzerType(): int
    {
        return (int) $this->query(
            "SELECT setting "
            . "FROM config "
            . "WHERE ID = 'CurrentBuzzerType'")
            ->fetch_assoc()['setting'];
    }
    
    /**
     * Set the next buzzer type
     * @param int $buzzerType
     */
    public function setCurrentBuzzerType(int $buzzerType)
    {
        $stmt = $this->prepare(
            "UPDATE config "
            . "SET setting=? "
            . "WHERE ID = 'CurrentBuzzerType'");
        $stmt->bind_param('i', $buzzerType);
        $stmt->execute();
        $stmt->close();
    }
}
