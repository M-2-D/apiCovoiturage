import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost:8000/api', // URL de votre API Laravel
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default instance;
