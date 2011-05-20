<?

	require_once 'Dummy.php';

	class DummyTest extends PHPUnit_Framework_TestCase {
		
		public function testSayAnything() {
			
			$message = 'Bla';
			$date = date('d.m.Y:');
			
			$say = new Dummy();
			$this->assertSame($date . $message, $say->say($message));
			
		}
	
	}
	
?>