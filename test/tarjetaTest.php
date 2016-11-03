<?php
namespace Poli\Tarjeta;
class TarjetaTest extends \PHPUnit_Framework_TestCase {
  protected $tarjeta,$colectivoA,$colectivoB,$medio,$bici;	
  public function setup(){
			$this->tarjeta = new Tarjeta(23);
      $this->medio = new Medio();
		  $this->colectivoA = new Colectivo("144 Negro", "Rosario Bus");
  		$this->colectivoB = new Colectivo("135", "Rosario Bus");
      $this->biciA = new Bicicleta("323");
      $this->biciB = new Bicicleta("111");
  }	
  public function testCargaSaldo() {
    $this->tarjeta->recargar(272);
    $this->assertEquals($this->tarjeta->saldo(), 320, "Cuando cargo 272 deberia tener finalmente 320");
    $this->tarjeta = new Tarjeta(45);
    $this->tarjeta->recargar(505);
    $this->assertEquals($this->tarjeta->saldo(), 645, "Cuando cargo 505 deberia tener finalmente 645");
  }
  public function testPagarViaje() {
  	$this->tarjeta->recargar(272);
    $this->assertEquals($this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50")->getTipo(),"Normal", "Cuando recargo y pago un colectivo deberia devolver un boleto de viaje Normal");
  	$this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  }
  public function testPagarViajeSinSaldo() {
  	$this->assertEquals($this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50")->getTipo(),"Plus", "Cuando no recargo y pago un colectivo deberia devolver un boleto de viaje plus");
    $this->assertEquals($this->tarjeta->saldo(),-8, "Cuando no recargo y pago el saldo deberia ser -8");
  }

  public function testPagarBici() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->biciA, "2016/06/30 22:54");
    $this->assertEquals($this->tarjeta->saldo(), 308, "Si tengo 320 y pago una bici deberia tener finalmente 308");
  }
  public function testPagarDosBiciUnDia() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->biciA, "2016/06/30 10:54");
    $this->tarjeta->pagar($this->biciB, "2016/06/30 22:54");
    $this->assertEquals($this->tarjeta->saldo(), 308, "Si tengo 320 y pago una bici deberia tener finalmente 308");
  }
  public function testPagarDosBiciDosDias() {
    $this->tarjeta->recargar(272);
    $this->tarjeta->pagar($this->biciA, "2016/06/27 10:54");
    $this->tarjeta->pagar($this->biciB, "2016/06/30 22:54");
    $this->assertEquals($this->tarjeta->saldo(), 296, "Si tengo 320 y pago una bici deberia tener finalmente 308");
  }
  public function testViaje(){
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivoA, "2016/06/30 22:50");
  	$this->assertEquals($this->tarjeta->viajesRealizados()["2016/06/30 22:50"]->getTipo(),"Viaje en Colectivo", "");
  	$this->assertEquals($this->tarjeta->viajesRealizados()["2016/06/30 22:50"]->getHorario(),"2016/06/30 22:50", "");
  	$this->assertEquals($this->tarjeta->viajesRealizados()["2016/06/30 22:50"]->getCosto(),8, "");
  	$this->assertEquals($this->tarjeta->viajesRealizados()["2016/06/30 22:50"]->getTransporte()->getNombreEmpresa(),"Rosario Bus", "");
  }
  
}
?>
