<?php

class Response{

	var $commands = array();

	function __construct($commands = null)
	{
		$this->emit_header();
		$this->commands = $commands;
		$this->execute();
	}

	function emit_header()
	{
		header('Content-Type: text/html; charset=utf-8');
	}

	function execute()
	{
		if(is_array($this->commands))
		{
			$dtb = new Dtb();
			foreach($this->commands as $command)
			{
				if(getVarIs('mode', $command->command))
				{
					if(! $command->hasRights())
						return;
					$command->generateValues();
					$dtb->prepareAndExecute($command->query->raw_query, $command->query->values);
					$command->onBeforeClose($command, $dtb);
				}
			}
		}
	}

}

?>
