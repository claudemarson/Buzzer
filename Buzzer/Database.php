<?php

namespace Buzzer;

use Buzzer\RandomString;

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
     * Get a list of all teams
     * @return string[][]
     */
    public function getAllTeams()
    {
        return $this->query(
            "SELECT ID, teamName "
            . "FROM team "
            . "ORDER BY teamName")
            ->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get a list of all team passcodes
     * @return string[][]
     */
    public function getAllTeamPasscodes()
    {
        return $this->query(
            "SELECT passcode "
            . "FROM team")
            ->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Add a team
     */
    public function addTeam()
    {
        $teamID = random_int(100000000, 999999999);
        $passcode = RandomString::randomString(10);
        
        $stmt = $this->prepare(
            'INSERT INTO team (ID, passcode) '
            . 'VALUES (?, ?)');
        $stmt->bind_param('is', $teamID, $passcode);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Remove a team
     * @param int $teamID the team's ID
     */
    public function removeTeam(int $teamID)
    {
        $stmt = $this->prepare(
            'DELETE FROM team '
            . 'WHERE ID=? '
            . 'LIMIT 1');
        $stmt->bind_param('i', $teamID);
        $stmt->execute();
        $stmt->close();
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
     * @return bool true: revealed, false: hidden
     */
    public function getScreenAnswerVisibility(): bool
    {
        return (bool) $this->query(
            "SELECT setting "
            . "FROM config "
            . "WHERE ID = 'AnswerVisibility'")
            ->fetch_assoc()['setting'];
    }
    
    /**
     * Set the visibility of the answers on the screen
     * @param bool $reveal true: reveal, false: hide
     */
    public function setScreenAnswerVisibility(bool $reveal)
    {
        $stmt = $this->prepare(
            "UPDATE config "
            . "SET setting=? "
            . "WHERE ID = 'AnswerVisibility'");
        $stmt->bind_param('i', $reveal);
        $stmt->execute();
        $stmt->close();
        
        $this->setScreenAnswerVisibilityChanged(true);
    }
    
    /**
     * Get the flag indicating that the answer visibility has been changed and
     * has not yet been processed by the screen event stream
     * @return bool true: has changed, false: has not changed
     */
    public function getScreenAnswerVisibilityChanged(): bool
    {
        return (bool) $this->query(
            "SELECT setting "
            . "FROM config "
            . "WHERE ID = 'AnswerVisibilityChanged'")
            ->fetch_assoc()['setting'];
    }
    
    /**
     * Set the flag indicating that the answer visibility has been changed and
     * has not yet been processed by the screen event stream
     * @param bool $hasChanged true: has changed, false: has not changed
     */
    public function setScreenAnswerVisibilityChanged(bool $hasChanged)
    {
        $stmt = $this->prepare(
            "UPDATE config "
            . "SET setting=? "
            . "WHERE ID = 'AnswerVisibilityChanged'");
        $stmt->bind_param('i', $hasChanged);
        $stmt->execute();
        $stmt->close();
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
