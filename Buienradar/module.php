<?php
	class Buienradar extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyString ('City','Groningen');

			$TEMPERATURE = $this->RegisterVariableFloat('TEMPERATURE','Temperatuur','~Temperature');
			$HUMIDITY = $this->RegisterVariableInteger('HUMIDITY','uchtvochtigheid','~Intensity.100');
			$AIRPRESSURE = $this->RegisterVariableFloat('AIRPRESSURE','Luchtdruk','~AirPressure.F');
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
		}

	}