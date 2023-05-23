import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { AlertService } from 'src/app/service/alert/alert.service';

@Injectable({
  providedIn: 'root'
})

export class RootService {

  baseURL = environment.baseURL;

    constructor(
        private http: HttpClient,
        private Alert: AlertService,
    ) { }
    
    post(path:any, data:any) {     
        const URL = `${this.baseURL}${path}`;
        return this.http.post(URL, data);
    }

    get(path:any) {     
      const URL = `${this.baseURL}${path}`;
      return this.http.get(URL);
    }

    handleMessage(response:any) { //handle error message on failure from backend
      let title = '';
      let message = '';
      if (response.error) {
        if (response.error.message) {
          title = response.error.message;
        }
        if (response.error.data) {
          const dataArray = Object.keys(response.error.data);
          if (dataArray.length > 0) {
            message = response.error.data[dataArray[0]][0];
          }
        }
  
        this.Alert.showToast("Warning",title,'red');
        
      }
    }
}
