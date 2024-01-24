import axios from 'axios';

const authService = {
  async login(email, password) {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/login', {
        email,
        password,
      });
      const token = response.data.token;

      // Almacena el token en localStorage o en las cookies
      localStorage.setItem('token', token);

      return true; // Autenticación exitosa
    } catch (error) {
      console.error('Error al iniciar sesión:', error);
      return false; // Autenticación fallida
    }
  },

  logout() {
    // Elimina el token almacenado
    localStorage.removeItem('token');
  },

  isAuthenticated() {
    // Verifica si el usuario está autenticado
    const token = localStorage.getItem('token');
    return token !== null;
  },

  // Otros métodos relacionados con la autenticación si es necesario
};

export default authService;