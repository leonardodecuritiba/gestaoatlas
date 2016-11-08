
var list_required_pjuridica = [
    'cnpj',
    'ie',
    'razao_social',
    'nome_fantasia',
    'ativ_economica',
    'sit_cad_vigente',
    'sit_cad_status',
    'data_sit_cad',
    'reg_apuracao',
    'data_credenciamento',
    'ind_obrigatoriedade',
    'data_ini_obrigatoriedade'];

$(document).ready(function(){

    /**
     * Created by Leonardo on 09/08/2016.
     */

    //REQUIRED PESSOA JURIDICA
    $.each(list_required_pjuridica, function(i,v){
        $("input[name=" + v + "]").attr('required', false);
    });

    //ISENÇÃO IE
    $('input[name="isencao_ie"]').on('ifToggled', function(event){
        $ie = $(this).parents().find('input[name=ie]');
        if(this.checked){
            $($ie).inputmask('setvalue', 'x');
            $($ie).prop('disabled',true);
            $($ie).prop('required',false);
        } else {
            $($ie).prop('disabled',false);
            $($ie).prop('required',true);
        }
    });
});