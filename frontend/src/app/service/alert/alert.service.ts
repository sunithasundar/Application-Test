import { Injectable } from '@angular/core';
import swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})

export class AlertService {

    showToast(icon='', title = '', message='' , time='2000')
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
            title: title,
            text: message,
            width: '500px',
            background: '#E5EBF8',
            showConfirmButton: true,
            buttonsStyling: false,
            reverseButtons: true
        })
    }
}
