import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

i18n.use(initReactI18next).init({
    resources: {
        en:{
            translations: {
                'Sign Up': 'Sign Up',
                'Login': 'Login',
                'Admin Login': 'Admin Login',
                'Password mismatch': 'Password mismatch',
                Username: 'Username',
                'Display Name': 'Display Name',
                Password: 'Password',
                'Password Repeat': 'Password Repeat',
                Logout: 'Logout',
                Users: 'Users',
                Next: 'next > ',
                Previous: '< previous',
                'Load Failure': 'Load Failure',
                'User Not Found': 'User Not Found',
                Edit: 'Edit',
                'Change Display Name': 'Change Display Name',
                Save: 'Save',
                Cancel: 'Cancel',
                'My Profile': 'My Profile',
                'Delete My Account': 'Delete My Account',
                'Are you sure to delete your account?': 'Are you sure to delete your account?'
            }
        },
        tr: {
            translations: {
                'Sign Up': 'Kayıt Ol',
                'Login': 'Giriş Yap',
                'Admin Login': 'Yönetici Girişi',
                'Password mismatch': 'Aynı şifreyi giriniz',
                Username: 'Kullanıcı Adı',
                'Display Name': 'Tercih Edilen İsim',
                Password: 'Şifre',
                'Password Repeat': 'Şifreyi Tekrarla',
                Logout: 'Çıkış',
                Users: 'Kullanıcılar',
                Next: 'sonraki >',
                Previous: '< önceki',
                'Load Failure': 'Liste Alınamadı',
                'User Not Found': 'Kullanıcı Bulunamadı',
                Edit: 'Düzenle',
                'Change Display Name': 'Görünür İsminizi Değiştirin',
                Save: 'Kaydet',
                Cancel: 'İptal',
                'My Profile': 'Profilim',
                'Delete My Account': 'Hesabımı Sil',
                'Are you sure to delete your account?': 'Hesabınızı silmek istediğinize emin misiniz?'
            }
        }
    },
    fallbackLng: 'en',
    ns: ['translations'],
    defaultNS: 'translations',
    keySeparator: false,
    interpolation: {
        escapeValue: false,
        formatSeparator: ','
    },
    react: {
        wait:true
    }
})

export default i18n;