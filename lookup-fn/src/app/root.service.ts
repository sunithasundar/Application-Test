import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RootService {

  baseURL = environment.baseURL;

    constructor(
        private http: HttpClient,
    ) { }
    
    post(path:any, data:any) {     
        const URL = `${this.baseURL}${path}`;
        return this.http.post(URL, data);
    }
}
