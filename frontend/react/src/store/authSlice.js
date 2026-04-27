import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import api from '../api/client'

export const sendOtp = createAsyncThunk('auth/sendOtp', async (data, { rejectWithValue }) => {
  try {
    const res = await api.post('/auth/otp/send', data)
    return res.data
  } catch (err) {
    return rejectWithValue(err.response?.data)
  }
})

export const verifyOtp = createAsyncThunk('auth/verifyOtp', async (data, { rejectWithValue }) => {
  try {
    const res = await api.post('/auth/otp/verify', data)
    return res.data
  } catch (err) {
    return rejectWithValue(err.response?.data)
  }
})

const authSlice = createSlice({
  name: 'auth',
  initialState: {
    token: localStorage.getItem('mds_token'),
    user: JSON.parse(localStorage.getItem('mds_user') || 'null'),
    loading: false,
    error: null,
  },
  reducers: {
    logout: (state) => {
      state.token = null
      state.user = null
      localStorage.removeItem('mds_token')
      localStorage.removeItem('mds_user')
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(verifyOtp.fulfilled, (state, action) => {
        state.token = action.payload.token
        state.user  = action.payload.user
        state.loading = false
        localStorage.setItem('mds_token', action.payload.token)
        localStorage.setItem('mds_user', JSON.stringify(action.payload.user))
      })
      .addCase(verifyOtp.pending,  (state) => { state.loading = true })
      .addCase(verifyOtp.rejected, (state, action) => {
        state.loading = false
        state.error   = action.payload?.message || 'OTP verification failed'
      })
  },
})

export const { logout } = authSlice.actions
export default authSlice.reducer
