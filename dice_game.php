<?php

Class Dice {

	public $players = array();
	public $player_dice = 6;
	public $result = array();
	public $displacement = array();

	private $dice_count = array();
	private $round = 1;

	protected $play = TRUE;

	function start()
	{
		if(!is_array($this->players) || empty($this->players))
			die('there are no players in this game...');

		foreach($this->players as $index => $label)
		{
			$this->dice_count[$index] = $this->player_dice;
		}

		while($this->play)
		{
			$this->roll_dice();
			$this->check_dice();
			$this->check_winner();

			$this->round++;
		}
	}

	function print_result()
	{
		for($i = 1; $i < $this->round; $i++)
		{
			$table = '<table border="1" cellpadding="5" cellspacing="1">';
			$table .= '<tr><td colspan="4">Round: '.$i.'</td></tr>';
			$table .= '<tr><td>Player</td><td>Dice Roll Result</td><td>Dice Displacement Result</td><td>Dice Remaining</td></tr>';
			
			foreach($this->players as $index => $player)
			{
				$table .= '<tr '.(count($this->displacement[$i][$index])==0 ? 'style="background:green;"' : '').'>';
				$table .= '<td>'.$player.'</td>';
				$table .= '<td>'.implode(',', $this->result[$i][$index]).'</td>';
				$table .= '<td>'.implode(',', $this->displacement[$i][$index]).'</td>';
				$table .= '<td>'.count($this->displacement[$i][$index]).'</td>';
				$table .= '</tr>';
			}
			
			$table .= '</table>';

			$table .= '<hr />';

			echo $table;
		}
	}

	private function roll_dice()
	{
		$result = array();
		foreach($this->players as $index => $label)
		{
			$result[$index] = $this->get_result($index);
			$this->displacement[$this->round][$index] = $result[$index];
		}

		$this->result[$this->round] = $result;
	}

	private function check_dice()
	{
		foreach ($this->result[$this->round] as $player => $dice) 
		{
			$dice_face_count = 0;

			foreach($dice as $dice_key => $dice_face)
			{
				if($dice_face == 1 && $dice_face_count == 0)
				{
					if(($player + 1) < count($this->players))
					{
						$this->displacement[$this->round][$player + 1][] = $dice_face;
						$this->dice_count[$player + 1] = count($this->displacement[$this->round][$player + 1]);
					}
					else
					{
						$this->displacement[$this->round][0][] = $dice_face;
						$this->dice_count[0] = count($this->displacement[$this->round][0]);
					}

					unset($this->displacement[$this->round][$player][$dice_key]);

					$dice_face_count = 1;
				}
				elseif($dice_face == 6)
				{
					unset($this->displacement[$this->round][$player][$dice_key]);
				}

				$this->dice_count[$player] = count($this->displacement[$this->round][$player]);
			}

		}
	}

	private function check_winner()
	{
		foreach ($this->dice_count as $player => $count) 
		{
			if(empty($count))
			{
				$this->play = FALSE;
			}
		}

	}

	private function get_result($player)
	{
		$roll_dice = array();

		for($i = 1; $i <= $this->dice_count[$player]; $i++)
			$roll_dice[] = rand(1, 6);
		
		return $roll_dice;
	}
}