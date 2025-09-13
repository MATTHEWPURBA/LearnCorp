import { NextRequest, NextResponse } from 'next/server'
import { auth } from '@clerk/nextjs/server'
import { getPresignedUploadUrl, getPresignedDownloadUrl } from '@/lib/r2'

export async function POST(request: NextRequest) {
  try {
    const { userId } = auth()
    
    if (!userId) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { key, contentType, operation = 'upload' } = body

    if (!key) {
      return NextResponse.json({ error: 'Key is required' }, { status: 400 })
    }

    let presignedUrl: string

    if (operation === 'download') {
      presignedUrl = await getPresignedDownloadUrl(key)
    } else {
      if (!contentType) {
        return NextResponse.json({ error: 'Content type is required for upload' }, { status: 400 })
      }
      presignedUrl = await getPresignedUploadUrl(key, contentType)
    }

    return NextResponse.json({ presignedUrl })
  } catch (error) {
    console.error('Error generating presigned URL:', error)
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 })
  }
}
