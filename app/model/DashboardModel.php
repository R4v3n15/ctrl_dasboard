<?php

class DashboardModel
{
	public static function calendarEvents(){
		$database = DatabaseFactory::getFactory()->getConnection();
		$events = $database->prepare("SELECT * FROM tasks");
		$events->execute();

		$eventos = [];
		if ($events->rowCount() > 0) {
			$events = $events->fetchAll();

			foreach ($events as $event) {
				switch ((int)$event->priority) {
					case 1: $color = '#FF7043'; break;
					case 2: $color = '#0288D1'; break;
					case 2: $color = '#4CAF50'; break;
					default: $color = '#0288D1'; break;
				}
				$evento = array(
							'title' => $event->title, 
							'start' => $event->created_at, 
							'end'   => $event->date_todo,
							'color' => $color
						);
				array_push($eventos, $evento);
			}
			
		}

		return $eventos;
	}


}
