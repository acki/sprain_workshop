<?

	class Member{
	
		public 	$id;
		protected 	$zonk;
		private 	$bla;
	
		public function __construct($id) {
			$this->id = $id;
		}
		
		public function setName($name) {
			$this->name = $name;
		}
		
		public function getName() {
			print $this->name;
		}
		
	}
	
?>