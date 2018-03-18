package test.testapplication;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.HashMap;

class NetworkUtil {

    static String SERVER_URL = "http://165.194.35.145/water/android.php";

    interface preExecutable {
        void execute();
    }

    interface postExecutable {
        void execute(JSONObject response);
    }

    static String urlBuilder(String url, HashMap<String, String> param) {

        if (param != null && !param.isEmpty()) {
            String parameter = "";
            for (String key : param.keySet()) {
                parameter += "&" + key + "=" + param.get(key);
            }
            url = url + "?" + parameter.substring(1);
        }

        return url;
    }

    static class requestGET extends AsyncTask<Void, Void, JSONObject> {

        private Context context;
        private String url;
        private preExecutable onPreExecuteJob;
        private postExecutable onPostExecuteJob;
        private ProgressDialog progressDialog;

        requestGET(Context context, String url, preExecutable onPreExecuteJob, postExecutable onPostExecuteJob) {
            this.context = context;
            this.progressDialog = new ProgressDialog(this.context);
            this.url = url;
            this.onPreExecuteJob = onPreExecuteJob;
            this.onPostExecuteJob = onPostExecuteJob;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            progressDialog.setMessage(this.context.getResources().getString(R.string.loading_msg));
            progressDialog.setCancelable(false);
            progressDialog.show();

            if (onPreExecuteJob != null) {
                onPreExecuteJob.execute();
            }
        }

        @Override
        protected JSONObject doInBackground(Void... voids) {

            HttpURLConnection urlConnection = null;
            int timeToRequest = 5;
            while (timeToRequest > 0) {
                try {
                    urlConnection = (HttpURLConnection) new URL(this.url).openConnection();
                    urlConnection.setRequestProperty("Accept-Charset", "UTF-8");
                    urlConnection.setRequestProperty("Accept", "application/json");

                    if (urlConnection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                        Log.i(this.getClass().getName(), "Request Failed: " + urlConnection.getResponseCode() + " " + urlConnection.getResponseMessage());
                        return null;
                    }

                    BufferedReader reader = new BufferedReader(new InputStreamReader(urlConnection.getInputStream(), "UTF-8"));

                    String line;
                    String response = "";
                    while ((line = reader.readLine()) != null) {
                        response += line;
                    }

                    reader.close();
                    urlConnection.disconnect();
                    Log.i(this.getClass().getName(), "Request Success");
                    //return response;

                    return new JSONObject(response);

                } catch (MalformedURLException e) {
                    Log.i(this.getClass().getName(), "Request Failed: Malformed URL: " + this.url);
                    //e.printStackTrace();
                    if (urlConnection != null) {
                        urlConnection.disconnect();
                    }
                    return null;
                } catch (IOException e) {
                    Log.i(this.getClass().getName(), "Request Failed: I/O Error");
                    e.printStackTrace();
                } catch (JSONException e) {
                    Log.i(this.getClass().getName(), "Response Error: Malformed JSON");
                    //e.printStackTrace();
                    return null;
                } finally {
                    if (urlConnection != null) {
                        urlConnection.disconnect();
                    }
                }
                timeToRequest--;
                Log.i(this.getClass().getName(), "Sending request again... TTR=" + timeToRequest);
            }
            Log.i(this.getClass().getName(), "Request Failed: TimeToRequest timeout");
            return null;
        }

        @Override
        protected void onPostExecute(JSONObject response) {
            super.onPostExecute(response);

            if (response == null) {
                Toast.makeText(this.context, this.context.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
            }
            else if (onPostExecuteJob != null) {
                onPostExecuteJob.execute(response);
            }

            progressDialog.dismiss();
        }
    }
}
