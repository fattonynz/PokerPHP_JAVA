/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pokergame;
import org.json.*;

/**
 *
 * @author SHER
 */
 class JsonWrapper {
 public  JSONObject obj; 
public JsonWrapper(String s){
 obj=new JSONObject(s);   
}    
public int get_int(String key){
    return obj.getInt(key);
}
 }