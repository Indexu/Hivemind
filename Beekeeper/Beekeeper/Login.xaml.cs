using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace Beekeeper
{
    /// <summary>
    /// Interaction logic for Login.xaml
    /// </summary>
    public partial class Login : Window
    {
        //Credidentials
        string username = "GRU_H1", password = "SkInDeXu";

        public Login()
        {
            InitializeComponent();
        }
        //Log Inn
        private void Button_Click_1(object sender, RoutedEventArgs e)
        {

            string user = Convert.ToString(input_username.Text);
            string pass = Convert.ToString(input_password.Password);

            if ((user == username) && (pass == password))
            {

                MainWindow shitwindow = new MainWindow();
                shitwindow.Show();
                this.Close();

            }
            else
            {


                MessageBox.Show("Invalid username or password");


            }

        }
        //Til að búa til placeholder notaði ég GotFocus og LostFocus og breyti litnum
        private void input_username_GotFocus(object sender, RoutedEventArgs e)
        {
            input_username.Text = "";
            input_username.Foreground = Brushes.Black;
        }

        private void input_password_GotFocus(object sender, RoutedEventArgs e)
        {
            input_password.Password = "";
            input_password.Foreground = Brushes.Black;
        }

        private void input_username_LostFocus(object sender, RoutedEventArgs e)
        {
            if (string.IsNullOrEmpty(input_username.Text))
            {
                input_username.Text = "Username";
                input_username.Foreground = Brushes.Gray;
            }
        }

        private void input_password_LostFocus(object sender, RoutedEventArgs e)
        {
            if (string.IsNullOrEmpty(input_password.Password))
            {
                input_password.Password = "Password";
                input_password.Foreground = Brushes.Gray;
            }
        }


    }
}
