import java.io.IOException;
import java.io.OutputStream;
import java.net.InetSocketAddress;

import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpExchange;

public class Main {

    public static void main(String[] args) throws Exception {
        String allowedIP = "127.0.0.1";
        int port = 8000;

        HttpServer server = HttpServer.create(new InetSocketAddress(port), 0);

        server.createContext("/", new MyHandler(allowedIP));

        server.start();
        System.out.println("Server started on port " + port);
    }

    static class MyHandler implements HttpHandler {
        private final String allowedIP;

        public MyHandler(String allowedIP) {
            this.allowedIP = allowedIP;
        }

        @Override
        public void handle(HttpExchange exchange) throws IOException {
            String clientIP = exchange.getRequestHeaders().getFirst("X-Forwarded-For");
            if (clientIP == null) {
                clientIP = exchange.getRequestHeaders().getFirst("Host");
            }

            if (clientIP == null) {
                System.out.println("Не удалось получить IP-адрес клиента");
                sendResponse(exchange, "500 Internal Server Error");
                return;
            }

            if (clientIP.equals(allowedIP)) {
                String response = "Hello, you have access!";
                sendResponse(exchange, response);
                System.out.println("Программа успешно выполнена");
            } else {
                sendResponse(exchange, "403 Forbidden");
                System.out.println("Не правильный IP");
            }
        }

        private void sendResponse(HttpExchange exchange, String response) throws IOException {
            exchange.sendResponseHeaders(200, response.length());
            OutputStream os = exchange.getResponseBody();
            os.write(response.getBytes());
            os.close();
        }
    }
}
