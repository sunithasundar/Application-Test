import { Injectable } from '@angular/core';
import swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})

export class AlertService {

    /**
   * @desc controlling toast configuring 
   * @param icon, message, backgrund, time
   * @return toast with configured background, mesage, etc
   */
    showToast(icon='', message='' , background='', time='2000')
    {
        var timer;        
        if(time)
        {
            timer=time;
        }
        else
        {
            timer='';
        }

        return swal.fire({
            timer: 2000,
            text: message,
            width: '400px',
            color: 'white',
            background: background,
            position: 'bottom-right',
            showConfirmButton: false,
            buttonsStyling: true,
            reverseButtons: true
        })
    }
}
