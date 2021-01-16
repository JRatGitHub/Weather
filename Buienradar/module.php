<?php
	class Buienradar extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyString ('City','Groningen');

			$TEMPERATURE = $this->RegisterVariableFloat('TEMPERATURE','Temperatuur','~Temperature');
			$HUMIDITY = $this->RegisterVariableInteger('HUMIDITY','Luchtvochtigheid','~Intensity.100');
			$AIRPRESSURE = $this->RegisterVariableFloat('AIRPRESSURE','Luchtdruk','~AirPressure.F');
			$FEELTEMPERATURE = $this->RegisterVariableFloat('FEELTEMPERATURE','Temperatuur','~Temperature');
			
			$RAINFALLLAST24HOUR = $this->RegisterVariableFloat('RAINFALLLAST24HOUR','regen laaste 24 uur','~Temperature');
			$RAINFALLLASTHOUR = $this->RegisterVariableFloat('RAINFALLLASTHOUR','regen laatste uur','~Temperature');
			
			$this->RegisterTimer('INTERVAL',10, 'BUIENRADAR_GetData($id)');
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


		protected function RegisterTimer($ident, $interval, $script) {
			$id = @IPS_GetObjectIDByIdent($ident, $this->InstanceID);
		
			if ($id && IPS_GetEvent($id)['EventType'] <> 1) {
			  IPS_DeleteEvent($id);
			  $id = 0;
			}
		
			if (!$id) {
			  $id = IPS_CreateEvent(1);
			  IPS_SetParent($id, $this->InstanceID);
			  IPS_SetIdent($id, $ident);
			}
		
			IPS_SetName($id, $ident);
			IPS_SetHidden($id, true);
			IPS_SetEventScript($id, "\$id = \$_IPS['TARGET'];\n$script;");
		
			if (!IPS_EventExists($id)) throw new Exception("Ident with name $ident is used for wrong object type");
		
			if (!($interval > 0)) {
			  IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, 1);
			  IPS_SetEventActive($id, false);
			} else {
			  IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $interval);
			  IPS_SetEventActive($id, true);
			}
		  }

		public function GetData()
		{
			$url = 'https://data.buienradar.nl/2.0/feed/json';
		//	print($url);
			$data = file_get_contents($url); // put the contents of the file into a variable
			$wizards = json_decode($data, true);
		//	SetValueFloat($this->GetIDForIdent('TEMPERATURE'),$wizards['0']['8']);
			// check of er ook echt data is
			$stationmeasurements = $wizards['actual']['stationmeasurements']['12'];
			print_r($stationmeasurements);		
			SetValueFloat($this->GetIDForIdent('TEMPERATURE'),$stationmeasurements['temperature']);
			SetValueInteger($this->GetIDForIdent('HUMIDITY'),$stationmeasurements['humidity']);
			SetValueFloat($this->GetIDForIdent('AIRPRESSURE'),$stationmeasurements['airpressure']);
			SetValueFloat($this->GetIDForIdent('FEELTEMPERATURE'),$stationmeasurements['feeltemperature']);
			SetValueFloat($this->GetIDForIdent('RAINFALLLAST24HOUR'),$stationmeasurements['rainFallLast24Hour']);
			SetValueFloat($this->GetIDForIdent('RAINFALLLASTHOUR'),$stationmeasurements['rainFallLastHour']);
			
		}

	}