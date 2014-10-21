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
using System.Net;
using System.Collections.Specialized;

namespace Beekeeper
{
    /// <summary>
    /// Interaction logic for newUser.xaml
    /// </summary>

    public partial class newUser : Window
    {
        hivemind hive = new hivemind();

        public newUser()
        {
            InitializeComponent();

            try
            {
                hive.connectDB();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString());
            }
        }

        private void Button_Click_1(object sender, RoutedEventArgs e)
        {
            string mail = input_mail.Text;
            string pass = input_pass.Password;
            string pass2 = input_pass2.Password;
            string alias = input_createalias.Text;
            string status = combo_status.Text;

            bool proceed = false;

            if (pass.Length < 5 )
            {
                MessageBox.Show("Too short");
            }

            else
            {
                if (pass.Length > 20 )
                {
                    MessageBox.Show("Too long");
                }

                else
                {
                    if (pass!=pass2)
                    {
                        MessageBox.Show("Passwords dont match");
                    }

                    else
                    {
                        proceed = true;
                    }
                }

            }

            if (proceed == true)
            {
                hive.Insert(mail, alias, status, 1);

                string url = "http://tsuts.tskoli.is/hopar/gru_h1/includes/updatePassword.php";
                string password = pass;

                using (WebClient client = new WebClient())
                {
                    var result = client.UploadString(url, "POST", password);
                }

                this.Close();
            }

        }
    }
}
