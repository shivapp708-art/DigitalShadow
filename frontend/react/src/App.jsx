import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { useSelector } from 'react-redux'

import LoginPage from './pages/LoginPage'
import DashboardPage from './pages/individual/DashboardPage'
import KycPage from './pages/individual/KycPage'
import ScanPage from './pages/individual/ScanPage'
import OrgDashboard from './pages/organization/OrgDashboard'
import OnboardingPage from './pages/organization/OnboardingPage'

function PrivateRoute({ children }) {
  const { token } = useSelector(state => state.auth)
  return token ? children : <Navigate to="/login" replace />
}

export default function App() {
  return (
    <BrowserRouter>
      <Toaster position="top-right" />
      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route path="/" element={<PrivateRoute><DashboardPage /></PrivateRoute>} />
        <Route path="/kyc" element={<PrivateRoute><KycPage /></PrivateRoute>} />
        <Route path="/scan" element={<PrivateRoute><ScanPage /></PrivateRoute>} />
        <Route path="/org/:orgId" element={<PrivateRoute><OrgDashboard /></PrivateRoute>} />
        <Route path="/org/onboard" element={<PrivateRoute><OnboardingPage /></PrivateRoute>} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  )
}
