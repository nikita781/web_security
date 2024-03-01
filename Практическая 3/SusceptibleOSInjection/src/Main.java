import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

public class Main {
    public static void main(String[] args) throws IOException {
        BufferedReader reader = new BufferedReader(new InputStreamReader(System.in));
        System.out.print("Введите команду: ");
        String command = reader.readLine();

        Process process = Runtime.getRuntime().exec(command);
        BufferedReader inputReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
        String line;
        while ((line = inputReader.readLine()) != null) {
            System.out.println(line);
        }
        inputReader.close();
    }
}
