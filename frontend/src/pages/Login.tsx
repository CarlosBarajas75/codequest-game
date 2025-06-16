import React, { useState } from 'react';
import fondo from '../assets/fondo.png';
import logo from '../assets/logocodequest.png';
import PhaserParticles from '../components/PhaserParticles';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [inputsActive, setInputsActive] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    try {
      const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await response.json();
      if (response.ok && data.token) {
        localStorage.setItem('token', data.token);
        window.location.href = '/';
      } else {
        setError(data.message || 'Error al iniciar sesión');
      }
    } catch (err) {
      setError('Error de conexión');
    }
  };

  return (
    <div
      className="min-h-screen bg-cover bg-center flex flex-col items-center justify-center"
      style={{ backgroundImage: `url(${fondo})` }}
    >
      <PhaserParticles active={inputsActive} />
      <div className="login-content bg-gradient-to-br from-white/10 to-white/30 backdrop-blur-xl border-2 border-yellow-200/40 rounded-3xl shadow-2xl p-8 flex flex-col items-center max-w-md w-full">
        <img src={logo} alt="Logo CodeQuest" className="login-logo mb-6 drop-shadow-lg animate-logo-magic" />
        <h2 className="text-2xl font-bold mb-6 text-center text-yellow-900 drop-shadow">Iniciar Sesión</h2>
        {error && <div className="mb-4 text-red-500">{error}</div>}
        <form onSubmit={handleSubmit} className="w-full flex flex-col gap-4">
          <input
            type="email"
            placeholder="Correo electrónico"
            value={email}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setEmail(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <input
            type="password"
            placeholder="Contraseña"
            value={password}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setPassword(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <button type="submit" className="w-full bg-gradient-to-r from-yellow-700 via-yellow-600 to-yellow-800 text-white p-2 rounded-lg font-bold shadow-lg hover:scale-105 hover:from-yellow-600 hover:to-yellow-900 transition">Entrar</button>
        </form>
      </div>
    </div>
  );
};

export default Login;
