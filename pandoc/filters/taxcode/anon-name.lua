function Span(span)
    if span.classes:includes('personaldata') and span.classes:includes('taxcode') then
        local content_string = pandoc.utils.stringify(span.content)
        content_string = "xxxxxx" .. string.sub(content_string, 7, string.len(content_string))
        span.content = pandoc.Str(content_string)
    end
    return span
    end