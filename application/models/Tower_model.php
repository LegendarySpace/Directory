<?php
    class Tower_model extends Directory_API {
		public function __construct()
		{
			parent::__construct();
			$this->table = 'Towers';
			$this->proper = array(
				'Name' => 'name',
				'AdminID' => 'admin',
				'Location' => 'location',
				'ManagementCompany' => 'management company',
				'ManagementContact' => 'phone',
				'ManagementContactEmail' => 'email',
				'ImageURL' => 'image',
				'Details' => 'details'
			);
		}

		public function get_splash($id = FALSE)
		{
			$data = $this->proper;

			return $this->splash($id, $data);
		}

		public function get_tiles($id = FALSE)
		{
			$data = null;

			if ($id) $data = $this->proper;
			else $data = array(
				// Tile values
				'Name' => 'name',
				'AccountID' => 'id',
				'Location' => 'aux'
			);

			return $this->tiles($id, $data);
		}

		public function item_struct()
        {
            // Array used by page to build forms
            return array(
                'name',
                'id',
                'admin',
                'location',
                'management',
                'phone',
                'email',
                'image',
                'details'
            );
        }

        public function create_item() {
			$data = $this->proper;

            return $this->create($data);
        }

        public function update_item($id = FALSE) {
            $data = $this->proper;

            return $this->update($id, $data);
        }

    }
